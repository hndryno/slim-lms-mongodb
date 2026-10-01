<?php

namespace App\Controllers;

use App\Services\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerController
{
    public function __construct(
        private CustomerService $customerService
    ) {
    }

    public function create(
        Request $request,
        Response $response
    ): Response {
        $data = json_decode(
            (string) $request->getBody(),
            true
        );

        $customer = $this->customerService->create($data);

        $response->getBody()->write(
            json_encode([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer,
            ])
        );

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}