<?php

namespace App\Routes;

use App\Controllers\CustomerController;
use Slim\Routing\RouteCollectorProxy;

class CustomerRoutes
{
    public static function register(
        RouteCollectorProxy $group,
        CustomerController $controller
    ): void {
        $group->post(
            '/customers',
            [$controller, 'create']
        );

        $group->get(
            '/customers',
            [$controller, 'index']
        );
    }
}