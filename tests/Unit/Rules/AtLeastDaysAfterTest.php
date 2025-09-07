<?php

namespace Tests\Unit\Rules;

use App\Rules\AtLeastDaysAfter;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AtLeastDaysAfterTest extends TestCase
{
    public function test_it_fails_when_less_than_min_days(): void
    {
        $data = [
            'available_from' => now()->format('Y-m-d'),
            'available_to' => now()->addDays(13)->format('Y-m-d'),
        ];

        $rules = [
            'available_to' => [new AtLeastDaysAfter('available_from', 14)],
        ];

        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('available_to', $validator->errors()->toArray());
    }

    public function test_it_passes_when_at_least_min_days(): void
    {
        $data = [
            'available_from' => now()->format('Y-m-d'),
            'available_to' => now()->addDays(14)->format('Y-m-d'),
        ];

        $rules = [
            'available_to' => [new AtLeastDaysAfter('available_from', 14)],
        ];

        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->fails());
    }
}


