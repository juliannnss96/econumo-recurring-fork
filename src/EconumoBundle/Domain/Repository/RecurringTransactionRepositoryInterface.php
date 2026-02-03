<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Repository;

use App\EconumoBundle\Domain\Entity\RecurringTransaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface RecurringTransactionRepositoryInterface
{
    public function save(RecurringTransaction $recurringTransaction): void;

    public function remove(RecurringTransaction $recurringTransaction): void;

    public function get(Id $id): RecurringTransaction;

    /**
     * @return RecurringTransaction[]
     */
    public function findByUserId(Id $userId): array;

    /**
     * @return RecurringTransaction[]
     */
    public function findDueTransactions(DateTimeInterface $date): array;
}
