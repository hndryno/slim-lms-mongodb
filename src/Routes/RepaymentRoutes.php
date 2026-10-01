<?php

namespace App\Routes;

use App\Controllers\RepaymentController;
use Slim\Routing\RouteCollectorProxy;

class RepaymentRoutes
{
    public static function register(
        RouteCollectorProxy $group,
        RepaymentController $controller
    ): void {
        $group->post(
            '/repayment', [$controller, 'create']
        );
    }
}