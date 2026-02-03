<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\RecurringTransaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\RecurringTransactionRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecurringTransactionRepository extends ServiceEntityRepository implements RecurringTransactionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurringTransaction::class);
    }

    public function save(RecurringTransaction $recurringTransaction): void
    {
        $this->_em->persist($recurringTransaction);
        $this->_em->flush();
    }

    public function remove(RecurringTransaction $recurringTransaction): void
    {
        $this->_em->remove($recurringTransaction);
        $this->_em->flush();
    }

    public function get(Id $id): RecurringTransaction
    {
        $result = $this->find($id);

        if (!$result instanceof RecurringTransaction) {
            throw new \RuntimeException(sprintf('Recurring transaction with id %s not found', $id));
        }

        return $result;
    }

    public function findByUserId(Id $userId): array
    {
        return $this->findBy(['user' => $userId]);
    }

    public function findDueTransactions(DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('rt')
            ->where('rt.isActive = :isActive')
            ->andWhere('rt.nextRunDate <= :date')
            ->setParameter('isActive', true)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
