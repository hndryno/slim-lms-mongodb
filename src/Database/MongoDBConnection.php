<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Database;

class MongoDBConnection
{
    private Database $database;

    public function __construct(
        string $uri,
        string $database
    ) {
        $client = new Client($uri);

        $this->database = $client->selectDatabase($database);
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
}