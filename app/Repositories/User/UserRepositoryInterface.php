<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getUserWithPaymentSum(string $userId): ?object;

    public function getUserWithPayments(string $userId, string $year, string $month): ?object;

    public function getUserWithPaymentsAndSum(string $userId, string $year, string $month): ?object;
}
