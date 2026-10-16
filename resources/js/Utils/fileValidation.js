/**
 * File Security & Validation Helper
 * Provides client-side validation to prevent executable / malicious uploads
 * and enforce allowed formats (images & pdf) and size limits.
 */

export const BLOCKED_EXTENSIONS = [
    'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'pht', 'phtml', 'phar', 'phps',
    'inc', 'pl', 'py', 'cgi', 'sh', 'bash', 'exe', 'bat', 'cmd', 'com', 'vbs',
    'vbe', 'js', 'jse', 'ws', 'wsf', 'wsc', 'msi', 'msp', 'scr', 'hta', 'cpl',
    'jar', 'gadget', 'term', 'reg', 'pif', 'htaccess', 'htpasswd', 'asp', 'aspx',
    'jsp', 'jspx', 'shtml', 'svg', 'html', 'htm'
];

export const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
export const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

export const ALLOWED_PDF_EXTENSIONS = ['pdf'];
export const ALLOWED_PDF_MIMES = ['application/pdf'];

export const DEFAULT_MAX_SIZE_BYTES = 5 * 1024 * 1024; // 5 MB

/**
 * Validate a File object from an <input type="file"> event.
 *
 * @param {File} file
 * @param {Object} options
 * @param {boolean} [options.allowImages=true]
 * @param {boolean} [options.allowPdf=false]
 * @param {number} [options.maxSize=DEFAULT_MAX_SIZE_BYTES]
 * @returns {{ valid: boolean, error?: string }}
 */
export function validateUploadedFile(file, options = {}) {
    const {
        allowImages = true,
        allowPdf = false,
        maxSize = DEFAULT_MAX_SIZE_BYTES
    } = options;

    if (!file) {
        return { valid: true };
    }

    // 1. Max size check
    if (file.size > maxSize) {
        const maxMb = (maxSize / (1024 * 1024)).toFixed(1);
        const actualMb = (file.size / (1024 * 1024)).toFixed(1);
        return {
            valid: false,
            error: `File size (${actualMb} MB) exceeds maximum allowed limit of ${maxMb} MB.`
        };
    }

    // 2. Prohibited characters / traversal check
    const filename = (file.name || '').toLowerCase();
    if (filename.includes('\0') || filename.includes('/') || filename.includes('\\')) {
        return {
            valid: false,
            error: 'File name contains invalid or dangerous characters.'
        };
    }

    // 3. Blocked executable extensions check (inspect all dot segments to prevent double extensions)
    const segments = filename.split('.');
    if (segments.length > 1) {
        segments.shift(); // remove base name
        for (const segment of segments) {
            const ext = segment.trim().toLowerCase();
            if (BLOCKED_EXTENSIONS.includes(ext)) {
                return {
                    valid: false,
                    error: `Executable files and scripts (.${ext}) are strictly prohibited.`
                };
            }
        }
    }

    // 4. Allowed formats check
    const lastExt = filename.split('.').pop().toLowerCase();
    const mime = (file.type || '').toLowerCase();

    const allowedExtensions = [];
    const allowedMimes = [];

    if (allowImages) {
        allowedExtensions.push(...ALLOWED_IMAGE_EXTENSIONS);
        allowedMimes.push(...ALLOWED_IMAGE_MIMES);
    }
    if (allowPdf) {
        allowedExtensions.push(...ALLOWED_PDF_EXTENSIONS);
        allowedMimes.push(...ALLOWED_PDF_MIMES);
    }

    const hasAllowedExt = allowedExtensions.includes(lastExt);
    const hasAllowedMime = mime === '' || allowedMimes.includes(mime);

    if (!hasAllowedExt || !hasAllowedMime) {
        const allowedDesc = (allowImages && allowPdf)
            ? 'JPG, PNG, WebP images or PDF documents'
            : (allowImages ? 'JPG, PNG, or WebP images' : 'PDF documents');
        return {
            valid: false,
            error: `Invalid file format. Only ${allowedDesc} are allowed.`
        };
    }

    return { valid: true };
}
