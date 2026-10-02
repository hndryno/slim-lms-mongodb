# LMS - Loan Management System

Backend API untuk Loan Management System menggunakan PHP, Slim Framework, dan MongoDB.

## Tech Stack

- PHP 8.4
- Slim Framework 4
- MongoDB
- Composer

## Features

- Customer CRUD
- Loan Management
- Loan Calculation
- Repayment
- Pagination
- MongoDB Aggregation Pipeline

## Installation

Clone repository:

```bash
git clone <repository-url>
cd slim-lms-mongodb
```

Install dependencies:

```bash
composer install
```

Copy `.env.example` menjadi `.env` dan sesuaikan konfigurasi MongoDB.

## Run

```bash
php -S localhost:8080 -t public
```

API:

```text
http://localhost:8080
```

## API Endpoints

### Customer

```text
POST   /customers
GET    /customers
GET    /customers/{id}
PUT    /customers/{id}
DELETE /customers/{id}
```

### Loan

```text
POST   /loans
GET    /loans
GET    /loans/{id}
```

### Repayment

```text
POST   /repayments
```

## Loan Calculation

```text
Interest Amount = Principal Amount × Interest Rate / 100
Total Amount    = Principal Amount + Interest Amount
```

## MongoDB Aggregation

MongoDB aggregation pipeline digunakan untuk mendapatkan informasi loan berdasarkan customer, termasuk jumlah loan dan total principal.

Pipeline tersedia di dokumentasi/source code project.

## Testing

API telah diuji menggunakan Postman.

Postman collection dan screenshot hasil testing tersedia pada file submission.

## Author

**Hendriyono**

GitHub: https://github.com/hndryno  
Email: hendriyono97@gmail.com