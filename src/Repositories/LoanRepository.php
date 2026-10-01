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

    private function formatLoan(array $loan): array
    {
        if (isset($loan['customer_id'])) {
            $loan['customer_id'] = (string) $loan['customer_id'];
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