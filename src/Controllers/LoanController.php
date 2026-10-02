<?php

namespace App\Controllers;

use App\Constants\HttpCode;
use App\Constants\Message;
use App\Helpers\ResponseHelper;
use App\Services\LoanService;
use App\Validators\LoanValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoanController
{
    public function __construct(
        private LoanService $loanService
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

        if (!is_array($data)) {
            return ResponseHelper::error(
                $response,
                HttpCode::BAD_REQUEST,
                Message::INVALID_REQUEST
            );
        }

        $errors = LoanValidator::validate($data);

        if (!empty($errors)) {
            return ResponseHelper::error(
                $response,
                HttpCode::UNPROCESSABLE_ENTITY,
                Message::VALIDATION_ERROR,
                $errors
            );
        }

        $loan = $this->loanService->create($data);

        if ($loan === null) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::CUSTOMER_NOT_FOUND
            );
        }

        return ResponseHelper::success(
            $response,
            HttpCode::CREATED,
            Message::LOAN_CREATED,
            $loan
        );
    }

    public function list(
        Request $request,
        Response $response
    ): Response {
        $params = $request->getQueryParams();

        $limit = isset($params['limit'])
            ? (int) $params['limit']
            : 10;

        $offset = isset($params['offset'])
            ? (int) $params['offset']
            : 0;

        if ($limit <= 0) {
            $limit = 10;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        $result = $this->loanService->findAll(
            $limit,
            $offset
        );

        return ResponseHelper::successWithPagination(
            $response,
            HttpCode::OK,
            Message::LOANS_RETRIEVED,
            $result['data'],
            [
                'limit' => $limit,
                'offset' => $offset,
                'total' => $result['total'],
            ]
        );
    }

    public function detail(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $id = $args['id'];

        if (!preg_match('/^[a-f\d]{24}$/i', $id)) {
            return ResponseHelper::error(
                $response,
                HttpCode::BAD_REQUEST,
                Message::INVALID_ID
            );
        }

        $loan = $this->loanService->findById($id);

        if ($loan === null) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::LOAN_NOT_FOUND
            );
        }

        return ResponseHelper::success(
            $response,
            HttpCode::OK,
            Message::LOAN_RETRIEVED,
            $loan
        );
    }
}