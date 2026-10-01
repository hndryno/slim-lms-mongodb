<?php

namespace App\Validators;

class RepaymentValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (
            !isset($data['loan_id']) ||
            trim((string) $data['loan_id']) === ''
        ) {
            $errors['loan_id'] = 'Loan ID is required';
        } elseif (
            !preg_match(
                '/^[a-f\d]{24}$/i',
                (string) $data['loan_id']
            )
        ) {
            $errors['loan_id'] = 'Loan ID must be a valid ID';
        }

        if (!isset($data['amount'])) {
            $errors['amount'] = 'Amount is required';
        } elseif (
            !is_numeric($data['amount']) ||
            (float) $data['amount'] <= 0
        ) {
            $errors['amount'] = 'Amount must be greater than 0';
        }

        return $errors;
    }
}