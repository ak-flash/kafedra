<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CorrectAnswerPresent implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        $correctAnswerPresent = false;

        foreach ($value as $val) {
            if($val['is_correct']) {
                $correctAnswerPresent = true;
            }
        }

        if (! $correctAnswerPresent) {
            $fail("Не указан ПРАВИЛЬНЫЙ ответ.");
        }
    }
}
