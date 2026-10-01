<?php

use App\Controllers\CustomerController;
use App\Controllers\LoanController;
use App\Database\MongoDBConnection;
use App\Repositories\CustomerRepository;
use App\Repositories\LoanRepository;
use App\Routes\CustomerRoutes;
use App\Routes\LoanRoutes;
use App\Services\CustomerService;
use App\Services\LoanService;
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

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Loan
|--------------------------------------------------------------------------
*/

$loanRepository = new LoanRepository(
    $mongo->getDatabase()->selectCollection('loans')
);

$loanService = new LoanService(
    $loanRepository,
    $customerRepository
);

$loanController = new LoanController(
    $loanService
);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

$app->group('/api/v1', function ($group) use (
    $customerController,
    $loanController
) {
    CustomerRoutes::register(
        $group,
        $customerController
    );

    LoanRoutes::register(
        $group,
        $loanController
    );
});

$app->run();