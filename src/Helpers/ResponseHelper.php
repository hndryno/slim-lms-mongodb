<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    public static function success(
        ResponseInterface $response,
        int $code,
        string $message,
        mixed $data = null
    ): ResponseInterface {
        $response->getBody()->write(
            json_encode([
                'code' => $code,
                'success' => true,
                'message' => $message,
                'data' => $data,
            ])
        );

        return $response
            ->withStatus($code)
            ->withHeader('Content-Type', 'application/json');
    }

    public static function error(
        ResponseInterface $response,
        int $code,
        string $message,
        mixed $data = null
    ): ResponseInterface {
        $response->getBody()->write(
            json_encode([
                'code' => $code,
                'success' => false,
                'message' => $message,
                'data' => $data,
            ])
        );

        return $response
            ->withStatus($code)
            ->withHeader('Content-Type', 'application/json');
    }
}