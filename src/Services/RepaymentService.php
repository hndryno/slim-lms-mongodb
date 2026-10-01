<?php

namespace App\Services;

use App\Repositories\LoanRepository;
use App\Repositories\RepaymentRepository;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class RepaymentService
{
    public function __construct(
        private RepaymentRepository $repaymentRepository,
        private LoanRepository $loanRepository
    ) {
    }

    public function create(array $data): array
    {
        $loan = $this->loanRepository->findById(
            $data['loan_id']
        );

        if ($loan === null) {
            return [
                'error' => 'loan_not_found',
            ];
        }

        $amount = (float) $data['amount'];

        $remainingAmount =
            (float) $loan['total_amount']
            - (float) $loan['paid_amount'];

        if ($amount > $remainingAmount) {
            return [
                'error' => 'amount_exceeds_remaining',
                'remaining_amount' => $remainingAmount,
            ];
        }

        $newPaidAmount =
            (float) $loan['paid_amount'] + $amount;

        $status = $newPaidAmount >=
            (float) $loan['total_amount']
            ? 'paid'
            : 'active';

        $now = new UTCDateTime();

        $repayment = $this->repaymentRepository->create([
            'loan_id' => new ObjectId(
                $data['loan_id']
            ),
            'amount' => $amount,
            'payment_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->loanRepository->updatePayment(
            $data['loan_id'],
            $amount,
            $status
        );

        return [
            'repayment' => $repayment,
            'loan' => [
                'id' => $loan['id'],
                'total_amount' => $loan['total_amount'],
                'paid_amount' => $newPaidAmount,
                'remaining_amount' =>
                    $loan['total_amount'] - $newPaidAmount,
                'status' => $status,
            ],
        ];
    }
}