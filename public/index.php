<?php

use App\Database\MongoDBConnection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mongo = new MongoDBConnection(
    $_ENV['MONGODB_URI'],
    $_ENV['MONGODB_DATABASE']
);

$app = AppFactory::create();

$app->get('/health', function (
    Request $request,
    Response $response
) use ($mongo) {
    $mongo->getDatabase()->command([
        'ping' => 1,
    ]);

    $response->getBody()->write(
        json_encode([
            'status' => 'ok',
            'message' => 'Slim API and MongoDB are running',
        ])
    );

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
});

$app->run();