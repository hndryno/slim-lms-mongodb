<?php

use App\Controllers\CustomerController;
use App\Database\MongoDBConnection;
use App\Repositories\CustomerRepository;
use App\Routes\CustomerRoutes;
use App\Services\CustomerService;
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

/*
|--------------------------------------------------------------------------
| Customer
|--------------------------------------------------------------------------
*/

$customerRepository = new CustomerRepository(
    $mongo->getDatabase()->selectCollection('customers')
);

$customerService = new CustomerService(
    $customerRepository
);

$customerController = new CustomerController(
    $customerService
);

CustomerRoutes::register(
    $app,
    $customerController
);

$app->run();