<?php

namespace App\Constants;

class Message
{
    // Customer
    public const CUSTOMER_CREATED = 'Customer created successfully';
    public const CUSTOMERS_RETRIEVED = 'Customers retrieved successfully';
    public const CUSTOMER_RETRIEVED = 'Customer retrieved successfully';
    public const CUSTOMER_UPDATED = 'Customer updated successfully';
    public const CUSTOMER_DELETED = 'Customer deleted successfully';
    public const CUSTOMER_NOT_FOUND = 'Customer not found';

    // Loan
    public const LOAN_CREATED = 'Loan created successfully';
    public const LOANS_RETRIEVED = 'Loans retrieved successfully';
    public const LOAN_RETRIEVED = 'Loan retrieved successfully';
    public const LOAN_NOT_FOUND = 'Loan not found';

    // Repayment
    public const REPAYMENT_CREATED = 'Repayment created successfully';
    public const PAYMENT_EXCEEDS_REMAINING = 'Payment amount exceeds remaining loan amount';

    // General
    public const VALIDATION_ERROR = 'Validation error';
    public const INVALID_REQUEST = 'Invalid request';
    public const INVALID_ID = 'Invalid ID';
    public const INTERNAL_SERVER_ERROR = 'Internal server error';
}