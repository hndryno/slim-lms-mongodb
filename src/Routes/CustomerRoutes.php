<?php

namespace App\Routes;

use App\Controllers\CustomerController;
use Slim\App;

class CustomerRoutes
{
    public static function register(
        App $app,
        CustomerController $controller
    ): void {
        $app->post(
            '/api/v1/customers',
            [$controller, 'create']
        );
    }
}