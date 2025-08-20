<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class FutureDate implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = Carbon::parse($value);
        $twoWeeksFromNow = now()->addWeeks(2);
        
        if ($date->lt($twoWeeksFromNow)) {
            $fail("The {$attribute} must be at least 2 weeks in the future.");
        }
    }
}
