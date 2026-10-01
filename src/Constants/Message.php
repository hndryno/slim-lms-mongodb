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

    // General
    public const INVALID_REQUEST = 'Invalid request';
    public const INVALID_ID = 'Invalid ID';
    public const INTERNAL_SERVER_ERROR = 'Internal server error';
}