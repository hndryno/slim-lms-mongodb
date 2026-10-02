<?php

namespace App\Repositories;

use MongoDB\BSON\ObjectId;
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

        $customer = [
            'id' => (string) $result->getInsertedId(),
            ...$data,
        ];

        return $this->formatCustomer($customer);
    }

    private function formatCustomer(array $customer): array
    {
        foreach ([
            'created_at',
            'updated_at',
        ] as $field) {
            if (
                isset($customer[$field]) &&
                $customer[$field] instanceof \MongoDB\BSON\UTCDateTime
            ) {
                $customer[$field] = $customer[$field]
                    ->toDateTime()
                    ->setTimezone(new \DateTimeZone('Asia/Jakarta'))
                    ->format('Y-m-d\TH:i:sP');
            }
        }

        return $customer;
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

    public function findById(string $id): ?array
    {
        $customer = $this->collection->findOne([
            '_id' => new ObjectId($id),
        ]);

        if ($customer === null) {
            return null;
        }

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

        return $customer;
    }

    public function update(string $id, array $data): ?array
    {
        $result = $this->collection->updateOne(
            [
                '_id' => new ObjectId($id),
            ],
            [
                '$set' => [
                    ...$data,
                    'updated_at' => new \MongoDB\BSON\UTCDateTime(),
                ],
            ]
        );

        if ($result->getMatchedCount() === 0) {
            return null;
        }

        return $this->findById($id);
    }

    public function delete(string $id): bool
    {
        $result = $this->collection->deleteOne([
            '_id' => new ObjectId($id),
        ]);

        return $result->getDeletedCount() > 0;
    }
}