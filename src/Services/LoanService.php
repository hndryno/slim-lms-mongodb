<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\LoanRepository;

class LoanService
{
    public function __construct(
        private LoanRepository $loanRepository,
        private CustomerRepository $customerRepository
    ) {
    }

    public function create(array $data): ?array
    {
        $customer = $this->customerRepository->findById(
            $data['customer_id']
        );

        if ($customer === null) {
            return null;
        }

        $principalAmount = (float) $data['principal_amount'];
        $interestRate = (float) $data['interest_rate'];

        $interestAmount = $principalAmount * ($interestRate / 100);

        $totalAmount = $principalAmount + $interestAmount;

        return $this->loanRepository->create([
            'customer_id' => new \MongoDB\BSON\ObjectId(
                $data['customer_id']
            ),
            'principal_amount' => $principalAmount,
            'interest_rate' => $interestRate,
            'paid_amount' => 0,
            'tenor' => (int) $data['tenor'],
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
            'status' => 'active',
            'start_date' => new \MongoDB\BSON\UTCDateTime(),
            'created_at' => new \MongoDB\BSON\UTCDateTime(),
            'updated_at' => new \MongoDB\BSON\UTCDateTime(),
        ]);
    }

    public function findAll(
        int $limit,
        int $offset
    ): array {
        return $this->loanRepository->findAll(
            $limit,
            $offset
        );
    }
}