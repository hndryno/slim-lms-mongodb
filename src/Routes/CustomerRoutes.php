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
        $group->post('/customer', [$controller, 'create']);
        $group->get('/customer/{id}', [$controller, 'detail']);
        $group->get('/customers', [$controller, 'list']);
        $group->put('/customer/{id}', [$controller, 'update']);
        $group->delete('/customer/{id}', [$controller, 'delete']);
    }
}