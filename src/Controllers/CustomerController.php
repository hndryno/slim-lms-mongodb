<?php

namespace App\Controllers;

use App\Services\CustomerService;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Constants\HttpCode;
use App\Constants\Message;

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

        return ResponseHelper::success(
            $response,
            HttpCode::CREATED,
            Message::CUSTOMER_CREATED,
            $customer
        );
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

        return ResponseHelper::success(
            $response,
            HttpCode::OK,
            Message::CUSTOMERS_RETRIEVED,
            [
                'items' => $result['data'],
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $result['total'],
                ],
            ]
        );
    }

    public function detail(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $customer = $this->customerService->findById($args['id']);

        if ($customer === null) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::CUSTOMER_NOT_FOUND
            );
        }

        return ResponseHelper::success(
            $response,
            HttpCode::OK,
            Message::CUSTOMER_RETRIEVED,
            $customer
        );
    }

    public function update(Request $request, Response $response, array $args): Response {
        $data = json_decode(
            (string) $request->getBody(),
            true
        );

        $customer = $this->customerService->update(
            $args['id'],
            $data
        );

        if ($customer === null) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::CUSTOMER_NOT_FOUND
            );
        }

        $response->getBody()->write(
            json_encode([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer,
            ])
        );

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function delete(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $isDeleted = $this->customerService->delete($args['id']);

        if (!$isDeleted) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::CUSTOMER_NOT_FOUND
            );
        }

       return ResponseHelper::success(
            $response,
            HttpCode::OK,
            Message::CUSTOMER_DELETED
        );
    }
}