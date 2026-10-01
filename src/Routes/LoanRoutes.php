<?php

namespace App\Routes;

use App\Controllers\LoanController;
use Slim\Routing\RouteCollectorProxy;

class LoanRoutes
{
    public static function register(
        RouteCollectorProxy $group,
        LoanController $controller
    ): void {
        $group->post('/loan', [$controller, 'create']);
    }
}