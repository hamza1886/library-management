<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIsbn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) !== 10) {
            return false;
        }

        $aggregate_number = 0;
        $characters = explode('', $value);

        foreach ($characters as $index => $character) {
            $digit = intval($character);
            $aggregate_number += $digit * (10 - $index);
        }

        return $aggregate_number % 11 === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid.';
    }
}
