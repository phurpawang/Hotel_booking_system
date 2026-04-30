<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BhutanPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove spaces, dashes, and plus sign
        $phone = preg_replace('/[\s\-\+]/', '', $value);
        
        // Bhutan country code is 975
        // Remove country code if present
        if (str_starts_with($phone, '975')) {
            $phone = substr($phone, 3);
        }
        
        // Valid Bhutan phone patterns:
        // Fixed line: 2-6 (area codes), 7 digits after
        // Mobile: 17, 77, 78 (area codes), 6 digits after
        // So total: 8 digits - starting with 2-6, 17, 77, 78
        
        $isValid = false;
        
        // Check if it's 8 digits
        if (strlen($phone) === 8 && ctype_digit($phone)) {
            $prefix = substr($phone, 0, 2);
            $firstDigit = (int)$phone[0];
            
            // Fixed line: 2-6 followed by 7 digits
            if ($firstDigit >= 2 && $firstDigit <= 6) {
                $isValid = true;
            }
            // Mobile: 17, 77, 78
            elseif (in_array($prefix, ['17', '77', '78'])) {
                $isValid = true;
            }
        }
        
        if (!$isValid) {
            $fail('The :attribute must be a valid Bhutan phone number (8 digits, e.g., 17123456, 2XXXXXX, 77123456).');
        }
    }
}
