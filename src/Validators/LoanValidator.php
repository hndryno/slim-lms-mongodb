<?php

namespace App\Validators;

class LoanValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (
            !isset($data['customer_id']) ||
            trim((string) $data['customer_id']) === ''
        ) {
            $errors['customer_id'] = 'Customer ID is required';
        } elseif (
            !preg_match(
                '/^[a-f\d]{24}$/i',
                (string) $data['customer_id']
            )
        ) {
            $errors['customer_id'] = 'Customer ID must be a valid ID';
        }

        if (!isset($data['principal_amount'])) {
            $errors['principal_amount'] = 'Principal amount is required';
        } elseif (
            !is_numeric($data['principal_amount']) ||
            (float) $data['principal_amount'] <= 0
        ) {
            $errors['principal_amount'] =
                'Principal amount must be greater than 0';
        }

        if (!isset($data['interest_rate'])) {
            $errors['interest_rate'] = 'Interest rate is required';
        } elseif (
            !is_numeric($data['interest_rate']) ||
            (float) $data['interest_rate'] < 0
        ) {
            $errors['interest_rate'] =
                'Interest rate must be greater than or equal to 0';
        }

        if (!isset($data['tenor'])) {
            $errors['tenor'] = 'Tenor is required';
        } elseif (
            !is_numeric($data['tenor']) ||
            (int) $data['tenor'] <= 0
        ) {
            $errors['tenor'] =
                'Tenor must be greater than 0';
        }

        return $errors;
    }
}