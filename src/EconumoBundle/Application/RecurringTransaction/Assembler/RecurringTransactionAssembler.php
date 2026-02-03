<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction\Assembler;

use App\EconumoBundle\Application\RecurringTransaction\Dto\CreateRecurringTransactionV1RequestDto;
use App\EconumoBundle\Application\RecurringTransaction\Dto\RecurringTransactionResultDto;
use App\EconumoBundle\Domain\Entity\RecurringTransaction;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoBundle\Domain\Repository\PayeeRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use DateTime;

class RecurringTransactionAssembler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly TagRepositoryInterface $tagRepository,
        private readonly PayeeRepositoryInterface $payeeRepository
    ) {
    }

    public function assembleRequestToEntity(CreateRecurringTransactionV1RequestDto $dto, Id $userId): RecurringTransaction
    {
        $user = $this->userRepository->get($userId);
        
        return new RecurringTransaction(
            new Id($dto->id),
            $user,
            TransactionType::createFromAlias($dto->type),
            $this->accountRepository->get(new Id($dto->accountId)),
            $dto->categoryId ? $this->categoryRepository->get(new Id($dto->categoryId)) : null,
            new DecimalNumber($dto->amount),
            $dto->description ?? '',
            $dto->payeeId ? $this->payeeRepository->get(new Id($dto->payeeId)) : null,
            $dto->tagId ? $this->tagRepository->get(new Id($dto->tagId)) : null,
            new RecurringInterval($dto->interval),
            DateTime::createFromFormat('Y-m-d H:i:s', $dto->startDate) ?: new DateTime($dto->startDate)
        );
    }

    public function assembleEntityToResultDto(RecurringTransaction $entity): RecurringTransactionResultDto
    {
        $dto = new RecurringTransactionResultDto();
        $dto->id = (string) $entity->getId();
        $dto->type = $entity->getType()->getAlias();
        $dto->accountId = (string) $entity->getAccount()->getId();
        $dto->amount = (string) $entity->getAmount();
        $dto->categoryId = $entity->getCategory() ? (string) $entity->getCategory()->getId() : null;
        $dto->description = $entity->getDescription();
        $dto->payeeId = $entity->getPayee() ? (string) $entity->getPayee()->getId() : null;
        $dto->tagId = $entity->getTag() ? (string) $entity->getTag()->getId() : null;
        $dto->interval = $entity->getInterval()->getValue();
        $dto->nextRunDate = $entity->getNextRunDate()->format('Y-m-d H:i:s');
        $dto->isActive = $entity->isActive();

        return $dto;
    }
}
