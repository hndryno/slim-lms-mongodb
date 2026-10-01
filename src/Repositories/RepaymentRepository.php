<?php

namespace App\Repositories;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class RepaymentRepository
{
    public function __construct(
        private Collection $collection
    ) {
    }

    public function create(array $data): array
    {
        $result = $this->collection->insertOne($data);

        $repayment = $data;

        $repayment['id'] = (string) $result->getInsertedId();

        return $this->formatRepayment($repayment);
    }

    private function formatRepayment(
        array $repayment
    ): array {
        if (isset($repayment['_id'])) {
            $repayment['id'] = (string) $repayment['_id'];
            unset($repayment['_id']);
        }

        if (isset($repayment['loan_id'])) {
            $repayment['loan_id'] =
                (string) $repayment['loan_id'];
        }

        foreach ([
            'payment_date',
            'created_at',
            'updated_at',
        ] as $field) {
            if (
                isset($repayment[$field]) &&
                $repayment[$field] instanceof UTCDateTime
            ) {
                $repayment[$field] = $repayment[$field]
                    ->toDateTime()
                    ->format(DATE_ATOM);
            }
        }

        return $repayment;
    }
}