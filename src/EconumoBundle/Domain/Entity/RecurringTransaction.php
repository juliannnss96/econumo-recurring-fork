<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity;

use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoBundle\Domain\Traits\EntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class RecurringTransaction
{
    use EntityTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private User $user,
        private TransactionType $type,
        private Account $account,
        private ?Category $category,
        private DecimalNumber $amount,
        private string $description,
        private ?Payee $payee,
        private ?Tag $tag,
        private RecurringInterval $interval,
        private DateTimeInterface $nextRunDate,
        private bool $isActive = true
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTime();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function getAmount(): DecimalNumber
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPayee(): ?Payee
    {
        return $this->payee;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function getInterval(): RecurringInterval
    {
        return $this->interval;
    }

    public function getNextRunDate(): DateTimeInterface
    {
        return $this->nextRunDate;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updated();
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updated();
    }

    public function updateNextRunDate(DateTimeInterface $date): void
    {
        $this->nextRunDate = $date;
        $this->updated();
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
