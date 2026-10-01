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

    public function findAll(int $limit, int $offset): array
    {
        $cursor = $this->collection->find(
            [],
            [
                'limit' => $limit,
                'skip' => $offset,
                'sort' => [
                    'created_at' => -1,
                ],
            ]
        );

        $customers = [];

        foreach ($cursor as $customer) {
            $customer = $customer->getArrayCopy();

            $customer['id'] = (string) $customer['_id'];

            unset($customer['_id']);

            if ($customer['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $customer['created_at'] = $customer['created_at']
                    ->toDateTime()
                    ->format('c');
            }

            if ($customer['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $customer['updated_at'] = $customer['updated_at']
                    ->toDateTime()
                    ->format('c');
            }

            $customers[] = $customer;
        }

        return [
            'data' => $customers,
            'total' => $this->collection->countDocuments(),
        ];
    }
}