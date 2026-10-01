<?php

namespace App\Controllers;

use App\Constants\HttpCode;
use App\Constants\Message;
use App\Helpers\ResponseHelper;
use App\Services\RepaymentService;
use App\Validators\RepaymentValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RepaymentController
{
    public function __construct(
        private RepaymentService $repaymentService
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

        $errors = RepaymentValidator::validate($data);

        if (!empty($errors)) {
            return ResponseHelper::error(
                $response,
                HttpCode::UNPROCESSABLE_ENTITY,
                Message::VALIDATION_ERROR,
                $errors
            );
        }

        $result = $this->repaymentService->create($data);

        if (
            isset($result['error']) &&
            $result['error'] === 'loan_not_found'
        ) {
            return ResponseHelper::error(
                $response,
                HttpCode::NOT_FOUND,
                Message::LOAN_NOT_FOUND
            );
        }

        if (
            isset($result['error']) &&
            $result['error'] === 'amount_exceeds_remaining'
        ) {
            return ResponseHelper::error(
                $response,
                HttpCode::BAD_REQUEST,
                Message::PAYMENT_EXCEEDS_REMAINING,
                [
                    'remaining_amount' =>
                        $result['remaining_amount'],
                ]
            );
        }

        return ResponseHelper::success(
            $response,
            HttpCode::CREATED,
            Message::REPAYMENT_CREATED,
            $result
        );
    }
}