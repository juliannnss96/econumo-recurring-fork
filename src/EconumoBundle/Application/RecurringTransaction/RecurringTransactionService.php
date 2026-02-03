<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction;

use App\EconumoBundle\Application\RecurringTransaction\Assembler\RecurringTransactionAssembler;
use App\EconumoBundle\Application\RecurringTransaction\Dto\CreateRecurringTransactionV1RequestDto;
use App\EconumoBundle\Application\RecurringTransaction\Dto\RecurringTransactionResultDto;
use App\EconumoBundle\Domain\Entity\RecurringTransaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use App\EconumoBundle\Domain\Repository\RecurringTransactionRepositoryInterface;
use App\EconumoBundle\Domain\Service\TransactionServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\TransactionDto;
use DateTimeImmutable;
use DateTime;
use DateInterval;

class RecurringTransactionService
{
    public function __construct(
        private readonly RecurringTransactionRepositoryInterface $recurringTransactionRepository,
        private readonly TransactionServiceInterface $transactionService,
        private readonly RecurringTransactionAssembler $assembler
    ) {
    }

    public function createRecurringTransaction(CreateRecurringTransactionV1RequestDto $request, Id $userId): RecurringTransactionResultDto
    {
        $transaction = $this->assembler->assembleRequestToEntity($request, $userId);

        $this->recurringTransactionRepository->save($transaction);

        return $this->assembler->assembleEntityToResultDto($transaction);
    }

    public function deleteRecurringTransaction(RecurringTransaction $recurringTransaction): void
    {
        $this->recurringTransactionRepository->remove($recurringTransaction);
    }

    public function getRecurringTransaction(Id $id): RecurringTransaction
    {
        return $this->recurringTransactionRepository->get($id);
    }

    /**
     * @return RecurringTransactionResultDto[]
     */
    public function listRecurringTransactions(Id $userId): array
    {
        $transactions = $this->recurringTransactionRepository->findByUserId($userId);
        
        return array_map(
            fn(RecurringTransaction $rt) => $this->assembler->assembleEntityToResultDto($rt),
            $transactions
        );
    }

    public function processDueTransactions(): int
    {
        $now = new DateTimeImmutable();
        $dueTransactions = $this->recurringTransactionRepository->findDueTransactions($now);
        $processedCount = 0;

        foreach ($dueTransactions as $recurring) {
            $this->createTransactionFromRecurring($recurring);
            $this->scheduleNextRun($recurring);
            $processedCount++;
        }

        return $processedCount;
    }

    private function createTransactionFromRecurring(RecurringTransaction $recurring): void
    {
        $dto = new TransactionDto();
        $dto->type = $recurring->getType();
        $dto->userId = $recurring->getUser()->getId();
        $dto->account = $recurring->getAccount();
        $dto->accountId = $recurring->getAccount()->getId();
        $dto->amount = $recurring->getAmount();
        $dto->description = $recurring->getDescription();
        $dto->date = $recurring->getNextRunDate();
        $dto->category = $recurring->getCategory();
        $dto->categoryId = $recurring->getCategory() ? $recurring->getCategory()->getId() : null;
        $dto->payee = $recurring->getPayee();
        $dto->payeeId = $recurring->getPayee() ? $recurring->getPayee()->getId() : null;
        $dto->tag = $recurring->getTag();
        $dto->tagId = $recurring->getTag() ? $recurring->getTag()->getId() : null;

        $this->transactionService->createTransaction($dto);
    }

    private function scheduleNextRun(RecurringTransaction $recurring): void
    {
        $currentRunDate = $recurring->getNextRunDate();
        // Ensure we are working with DateTime object for modification
        if ($currentRunDate instanceof DateTimeImmutable) {
            $nextRunDate = DateTime::createFromImmutable($currentRunDate);
        } else {
            $nextRunDate = clone $currentRunDate;
        }
        
        $interval = match ($recurring->getInterval()->getValue()) {
            RecurringInterval::DAILY => 'P1D',
            RecurringInterval::WEEKLY => 'P1W',
            RecurringInterval::MONTHLY => 'P1M',
            RecurringInterval::YEARLY => 'P1Y',
            default => 'P1M',
        };

        $nextRunDate->add(new DateInterval($interval));
        $recurring->updateNextRunDate($nextRunDate);

        $this->recurringTransactionRepository->save($recurring);
    }
}
