<?php

namespace Tests\Unit;

use App\Services\FileSecurityService;
use App\Rules\SecureFileUpload;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileSecurityTest extends TestCase
{
    /**
     * Test valid image file passes validation.
     */
    public function test_valid_image_passes(): void
    {
        $file = UploadedFile::fake()->image('test_logo.png', 200, 200);

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: false);

        $this->assertTrue($isValid);
        $this->assertNull($error);
        $this->assertEquals('png', $safeExt);
    }

    /**
     * Test valid PDF passes when allowPdf is true.
     */
    public function test_valid_pdf_passes_when_allowed(): void
    {
        // Fake PDF with proper PDF header
        $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n>>\nendobj\ntrailer\n<<\n>>\n%%EOF";
        $tempPath = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($tempPath, $pdfContent);

        $file = new UploadedFile($tempPath, 'document.pdf', 'application/pdf', null, true);

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: true);

        @unlink($tempPath);

        $this->assertTrue($isValid);
        $this->assertNull($error);
        $this->assertEquals('pdf', $safeExt);
    }

    /**
     * Test valid PDF is rejected when only images are allowed.
     */
    public function test_pdf_rejected_when_images_only(): void
    {
        $pdfContent = "%PDF-1.4\n%%EOF";
        $tempPath = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($tempPath, $pdfContent);

        $file = new UploadedFile($tempPath, 'document.pdf', 'application/pdf', null, true);

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: false);

        @unlink($tempPath);

        $this->assertFalse($isValid);
        $this->assertStringContainsString('Invalid file type', $error);
    }

    /**
     * Test executable PHP file is strictly blocked.
     */
    public function test_php_file_is_strictly_blocked(): void
    {
        $file = UploadedFile::fake()->create('shell.php', 10, 'application/x-php');

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: true);

        $this->assertFalse($isValid);
        $this->assertStringContainsString('Executable files and scripts', $error);
    }

    /**
     * Test double extension attack (e.g. shell.php.png) is strictly blocked.
     */
    public function test_double_extension_attack_is_blocked(): void
    {
        $file = UploadedFile::fake()->image('exploit.php.png', 100, 100);

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: true);

        $this->assertFalse($isValid);
        $this->assertStringContainsString('Executable files and scripts', $error);
    }

    /**
     * Test embedded PHP payload inside fake image (polyglot) is blocked.
     */
     public function test_embedded_php_payload_in_image_is_blocked(): void
     {
         // 1x1 PNG header followed by PHP script code
         $pngHeader = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==");
         $maliciousContent = $pngHeader . "<?php system(\$_GET['cmd']); ?>";

         $file = UploadedFile::fake()->createWithContent('avatar.png', $maliciousContent);

         [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: true);

         $this->assertFalse($isValid);
         $this->assertStringContainsString('suspicious or executable script code', $error);
     }

    /**
     * Test safe random filename generation never trusts original name.
     */
    public function test_safe_filename_generation(): void
    {
        $filename = FileSecurityService::generateSafeFilename('png', 'logo');

        $this->assertStringStartsWith('logo_', $filename);
        $this->assertStringEndsWith('.png', $filename);
        $this->assertDoesNotMatchRegularExpression('/php/i', $filename);
    }

    /**
     * Test oversized file is rejected.
     */
    public function test_oversized_file_is_rejected(): void
    {
        // 6 MB file
        $file = UploadedFile::fake()->create('large.jpg', 6144, 'image/jpeg');

        [$isValid, $error, $safeExt] = FileSecurityService::validate($file, allowImages: true, allowPdf: false, maxSizeKb: 5120);

        $this->assertFalse($isValid);
        $this->assertStringContainsString('exceeds the maximum allowed limit', $error);
    }

    /**
     * Test SecureFileUpload rule passes for valid image.
     */
    public function test_secure_file_upload_rule_passes_valid_image(): void
    {
        $file = UploadedFile::fake()->image('valid.png', 100, 100);

        $validator = validator(
            ['avatar' => $file],
            ['avatar' => [new SecureFileUpload(isImageOnly: true)]]
        );

        $this->assertTrue($validator->passes());
    }

    /**
     * Test SecureFileUpload rule rejects PHP file.
     */
    public function test_secure_file_upload_rule_rejects_php_file(): void
    {
        $file = UploadedFile::fake()->create('malicious.php', 10, 'application/x-php');

        $validator = validator(
            ['avatar' => $file],
            ['avatar' => [new SecureFileUpload(isImageOnly: true)]]
        );

        $this->assertFalse($validator->passes());
    }

    /**
     * Test SecureFileUpload rule allows string paths (for update preserving old file).
     */
    public function test_secure_file_upload_rule_allows_retained_string_path(): void
    {
        $validator = validator(
            ['logo' => 'storage/company_file/logo/old_logo.png'],
            ['logo' => [new SecureFileUpload(isImageOnly: true)]]
        );

        $this->assertTrue($validator->passes());
    }
}

