<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getUserWithPaymentSum(string $userId, string $year = '', bool $getCategory = false): ?object;

    public function getUserWithPayments(string $userId, string $year, string $month): ?object;

    public function getUserWithPaymentsAndSum(string $userId, string $year, string $month): ?object;
}
