<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastDaysAfter implements ValidationRule, DataAwareRule
{
    private array $data = [];

    public function __construct(
        private readonly string $otherField,
        private readonly int $minDays
    ) {}

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $other = $this->data[$this->otherField] ?? null;

        if (empty($other) || empty($value)) {
            return;
        }

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
        if (!is_string($other) || !preg_match($pattern, $other)) {
            return;
        }
        if (!is_string($value) || !preg_match($pattern, (string) $value)) {
            return;
        }

        try {
            $from = Carbon::createFromFormat('Y-m-d', (string) $other)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d', (string) $value)->startOfDay();
        } catch (\Throwable) {
            return;
        }
     

        if ($from->diffInDays($to) < $this->minDays) {
            $fail("Available To must be at least {$this->minDays} days after Available From.");
        }
    }
}


