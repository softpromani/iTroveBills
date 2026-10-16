<?php

namespace App\Rules;

use App\Services\FileSecurityService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SecureFileUpload implements ValidationRule
{
    protected bool $isImageOnly;
    protected bool $allowPdf;
    protected int $maxSizeKb;

    /**
     * @param bool $isImageOnly
     * @param bool $allowPdf
     * @param int $maxSizeKb
     */
    public function __construct(
        bool $isImageOnly = true,
        bool $allowPdf = false,
        int $maxSizeKb = FileSecurityService::DEFAULT_MAX_SIZE_KB
    ) {
        $this->isImageOnly = $isImageOnly;
        $this->allowPdf = $allowPdf;
        $this->maxSizeKb = $maxSizeKb;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If empty or null, pass (let 'required' rule handle emptiness if needed)
        if ($value === null || $value === '') {
            return;
        }

        // If existing file path string is passed (e.g. during company update when old image is retained)
        if (is_string($value)) {
            // Ensure no path traversal in retained strings
            if (str_contains($value, '..') || str_contains($value, "\0")) {
                $fail("The {$attribute} contains an invalid path.");
            }
            return;
        }

        if (!$value instanceof UploadedFile) {
            $fail("The {$attribute} must be a valid uploaded file.");
            return;
        }

        [$isValid, $errorMessage] = FileSecurityService::validate(
            $value,
            allowImages: $this->isImageOnly || $this->allowPdf,
            allowPdf: $this->allowPdf,
            maxSizeKb: $this->maxSizeKb
        );

        if (!$isValid) {
            $fail($errorMessage ?: "The {$attribute} failed security validation.");
        }
    }
}
