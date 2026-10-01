<?php

namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {
    }

    public function create(array $data): array
    {
        return $this->customerRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'created_at' => new \MongoDB\BSON\UTCDateTime(),
            'updated_at' => new \MongoDB\BSON\UTCDateTime(),
        ]);
    }

    public function findAll(int $limit, int $offset): array
    {
        return $this->customerRepository->findAll(
            $limit,
            $offset
        );
    }

    public function findById(string $id): ?array
    {
        return $this->customerRepository->findById($id);
    }
}