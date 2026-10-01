<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/health', function (Request $request, Response $response) {
    $response->getBody()->write(
        json_encode([
            'status' => 'ok',
            'message' => 'Slim API is running',
        ])
    );

    return $response
        ->withHeader('Content-Type', 'application/json');
});

$app->run();