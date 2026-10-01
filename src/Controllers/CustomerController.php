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

    public function list(
        Request $request,
        Response $response
    ): Response {
        $queryParams = $request->getQueryParams();

        $limit = isset($queryParams['limit'])
            ? (int) $queryParams['limit']
            : 10;

        $offset = isset($queryParams['offset'])
            ? (int) $queryParams['offset']
            : 0;

        $result = $this->customerService->findAll(
            $limit,
            $offset
        );

        $response->getBody()->write(
            json_encode([
                'success' => true,
                'message' => 'Customers retrieved successfully',
                'data' => $result['data'],
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $result['total'],
                ],
            ])
        );

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function detail(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $customer = $this->customerService->findById($args['id']);

        if ($customer === null) {
            $response->getBody()->write(
                json_encode([
                    'success' => false,
                    'message' => 'Customer not found',
                    'data' => null,
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(
            json_encode([
                'success' => true,
                'message' => 'Customer retrieved successfully',
                'data' => $customer,
            ])
        );

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}