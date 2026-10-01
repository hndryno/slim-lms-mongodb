<?php

namespace App\Repositories;

use MongoDB\Collection;

class CustomerRepository
{
    private Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function create(array $data): array
    {
        $result = $this->collection->insertOne($data);

        return [
            'id' => (string) $result->getInsertedId(),
            ...$data,
        ];
    }
}