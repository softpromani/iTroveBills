<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileSecurityService
{
    /**
     * Dangerous executable and script extensions that MUST NEVER be allowed.
     */
    public const BLOCKED_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'pht', 'phtml', 'phar', 'phps',
        'inc', 'pl', 'py', 'cgi', 'sh', 'bash', 'exe', 'bat', 'cmd', 'com', 'vbs',
        'vbe', 'js', 'jse', 'ws', 'wsf', 'wsc', 'msi', 'msp', 'scr', 'hta', 'cpl',
        'jar', 'gadget', 'term', 'reg', 'pif', 'htaccess', 'htpasswd', 'asp', 'aspx',
        'jsp', 'jspx', 'shtml', 'svg'
    ];

    /**
     * Whitelist of image mime types to safe extension mapping.
     */
    public const ALLOWED_IMAGE_MIMES = [
        'image/jpeg'  => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png'   => 'png',
        'image/x-png' => 'png',
        'image/webp'  => 'webp',
    ];

    /**
     * Whitelist of document mime types to safe extension mapping.
     */
    public const ALLOWED_DOCUMENT_MIMES = [
        'application/pdf'   => 'pdf',
        'application/x-pdf' => 'pdf',
    ];

    /**
     * Default maximum file size in kilobytes (5 MB).
     */
    public const DEFAULT_MAX_SIZE_KB = 5120;

    /**
     * Validate an uploaded file for security, format, size, and content.
     *
     * @param UploadedFile $file
     * @param bool $allowImages
     * @param bool $allowPdf
     * @param int $maxSizeKb
     * @return array [bool $isValid, string|null $errorMessage, string|null $safeExtension]
     */
    public static function validate(
        UploadedFile $file,
        bool $allowImages = true,
        bool $allowPdf = false,
        int $maxSizeKb = self::DEFAULT_MAX_SIZE_KB
    ): array {
        if (!$file->isValid()) {
            return [false, 'The uploaded file is invalid or could not be uploaded.', null];
        }

        // 1. File size check
        $sizeInKb = ceil($file->getSize() / 1024);
        if ($sizeInKb > $maxSizeKb) {
            $maxMb = round($maxSizeKb / 1024, 1);
            return [false, "File size ({$sizeInKb} KB) exceeds the maximum allowed limit of {$maxMb} MB.", null];
        }

        // 2. Strict filename security check
        $originalName = strtolower($file->getClientOriginalName());

        // Reject null bytes, path traversal or control characters
        if (str_contains($originalName, "\0") || str_contains($originalName, '..') || str_contains($originalName, '/') || str_contains($originalName, '\\')) {
            return [false, 'Filename contains prohibited or dangerous characters.', null];
        }

        // Inspect every dot-separated segment to prevent double extensions (e.g., test.php.png)
        $segments = explode('.', $originalName);
        if (count($segments) > 1) {
            array_shift($segments); // Remove the base name
            foreach ($segments as $segment) {
                $ext = trim($segment);
                if (in_array($ext, self::BLOCKED_EXTENSIONS, true)) {
                    return [false, 'Executable files and scripts (.php, .phtml, .exe, etc.) are strictly prohibited.', null];
                }
            }
        }

        $clientExt = strtolower($file->getClientOriginalExtension());
        if (in_array($clientExt, self::BLOCKED_EXTENSIONS, true)) {
            return [false, 'Executable files and scripts are strictly prohibited.', null];
        }

        // 3. MIME type inspection based on real file contents
        $realPath = $file->getRealPath() ?: $file->getPathname();
        $detectedMime = $file->getMimeType();

        if (!$detectedMime && $realPath && file_exists($realPath)) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $detectedMime = @finfo_file($finfo, $realPath) ?: null;
                @finfo_close($finfo);
            }
        }

        $allowedMimes = [];
        if ($allowImages) {
            $allowedMimes = array_merge($allowedMimes, self::ALLOWED_IMAGE_MIMES);
        }
        if ($allowPdf) {
            $allowedMimes = array_merge($allowedMimes, self::ALLOWED_DOCUMENT_MIMES);
        }

        if (!isset($allowedMimes[$detectedMime])) {
            $typeMsg = ($allowImages && $allowPdf)
                ? 'an image (JPG, PNG, WebP) or a PDF document'
                : ($allowImages ? 'an image (JPG, PNG, WebP)' : 'a PDF document');

            return [false, "Invalid file type. Allowed formats: {$typeMsg}.", null];
        }

        $safeExtension = $allowedMimes[$detectedMime];

        // 4. Deep content scan for PHP / script execution payload (preventing polyglot webshells)
        $sampleContent = false;
        if ($file instanceof \Illuminate\Http\Testing\File && isset($file->tempFile) && is_resource($file->tempFile)) {
            rewind($file->tempFile);
            $sampleContent = stream_get_contents($file->tempFile);
        } else {
            $path = $file->getRealPath() ?: $file->getPathname();
            if ($path && file_exists($path)) {
                $sampleContent = @file_get_contents($path, false, null, 0, 1024 * 512);
            }
            if ($sampleContent === false || $sampleContent === '') {
                try {
                    $sampleContent = $file->getContent();
                } catch (\Throwable $e) {
                }
            }
        }
        if ($sampleContent !== false && $sampleContent !== '') {
            $dangerousSignatures = [
                '/<\?php/i',
                '/<\?=/i',
                '/<\s*script\s+language\s*=\s*["\']?php/i',
                '/<%/i',
                '/__halt_compiler/i',
            ];
            foreach ($dangerousSignatures as $signature) {
                if (preg_match($signature, $sampleContent)) {
                    return [false, 'The file contains suspicious or executable script code.', null];
                }
            }
        }

        // 5. Image-specific validation (dimensions & image integrity)
        if (str_starts_with($detectedMime, 'image/')) {
            $imageDetails = @getimagesize($realPath);
            if ($imageDetails === false || empty($imageDetails[0]) || empty($imageDetails[1])) {
                return [false, 'The uploaded image appears corrupted or is not a valid image format.', null];
            }
        }

        // 6. PDF-specific validation (magic bytes header check)
        if ($detectedMime === 'application/pdf' || $detectedMime === 'application/x-pdf') {
            $handle = @fopen($realPath, 'rb');
            if ($handle) {
                $header = fread($handle, 5);
                fclose($handle);
                if ($header !== '%PDF-') {
                    return [false, 'The uploaded file does not have a valid PDF header.', null];
                }
            }
        }

        return [true, null, $safeExtension];
    }

    /**
     * Generate a cryptographically secure random filename with a safe extension.
     * Never trusts or uses the original user-supplied extension.
     *
     * @param string $safeExtension
     * @param string $prefix
     * @return string
     */
    public static function generateSafeFilename(string $safeExtension, string $prefix = ''): string
    {
        $ext = ltrim(strtolower($safeExtension), '.');
        $random = Str::random(24);
        $timestamp = date('YmdHis');

        return ($prefix ? $prefix . '_' : '') . "{$timestamp}_{$random}.{$ext}";
    }

    /**
     * Check if a given extension is considered dangerous.
     *
     * @param string $extension
     * @return bool
     */
    public static function isBlockedExtension(string $extension): bool
    {
        return in_array(strtolower(ltrim($extension, '.')), self::BLOCKED_EXTENSIONS, true);
    }
}
