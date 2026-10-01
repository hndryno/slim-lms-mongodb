<?php

namespace App\Repositories;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class LoanRepository
{
    public function __construct(
        private Collection $collection
    ) {
    }

    public function create(array $data): array
    {
        $result = $this->collection->insertOne($data);

        $loan = $data;

        $loan['id'] = (string) $result->getInsertedId();

        return $this->formatLoan($loan);
    }

    public function findAll(
        int $limit,
        int $offset
    ): array {
        $pipeline = [
            [
                '$lookup' => [
                    'from' => 'customers',
                    'localField' => 'customer_id',
                    'foreignField' => '_id',
                    'as' => 'customer',
                ],
            ],
            [
                '$unwind' => [
                    'path' => '$customer',
                    'preserveNullAndEmptyArrays' => true,
                ],
            ],
            [
                '$sort' => [
                    'created_at' => -1,
                ],
            ],
            [
                '$skip' => $offset,
            ],
            [
                '$limit' => $limit,
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'customer_id' => 1,
                    'principal_amount' => 1,
                    'interest_rate' => 1,
                    'tenor' => 1,
                    'interest_amount' => 1,
                    'total_amount' => 1,
                    'paid_amount' => 1,
                    'status' => 1,
                    'start_date' => 1,
                    'created_at' => 1,
                    'updated_at' => 1,
                    'customer.name' => 1,
                    'customer.email' => 1,
                ],
            ],
        ];

        $cursor = $this->collection->aggregate($pipeline);

        $loans = [];

        foreach ($cursor as $loan) {
            $loans[] = $this->formatLoan(
                $loan->getArrayCopy()
            );
        }

        $total = $this->collection->countDocuments();

        return [
            'data' => $loans,
            'total' => $total,
        ];
    }

    private function formatLoan(array $loan): array
    {
        if (isset($loan['_id'])) {
            $loan['id'] = (string) $loan['_id'];
            unset($loan['_id']);
        }

        if (isset($loan['customer_id'])) {
            $loan['customer_id'] = (string) $loan['customer_id'];
        }

        if (isset($loan['customer'])) {
            $loan['customer'] = [
                'name' => $loan['customer']['name'] ?? null,
                'email' => $loan['customer']['email'] ?? null,
            ];
        }

        foreach ([
            'start_date',
            'created_at',
            'updated_at',
        ] as $field) {
            if (
                isset($loan[$field]) &&
                $loan[$field] instanceof UTCDateTime
            ) {
                $loan[$field] = $loan[$field]
                    ->toDateTime()
                    ->format(DATE_ATOM);
            }
        }

        return $loan;
    }
}