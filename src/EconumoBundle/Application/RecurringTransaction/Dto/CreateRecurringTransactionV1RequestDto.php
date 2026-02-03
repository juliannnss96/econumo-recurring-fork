<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction\Dto;

use App\EconumoBundle\Domain\Entity\Account;
use App\EconumoBundle\Domain\Entity\Category;
use App\EconumoBundle\Domain\Entity\Payee;
use App\EconumoBundle\Domain\Entity\Tag;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use DateTimeInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "type", "amount", "accountId", "interval", "startDate"}
 * )
 */
final class CreateRecurringTransactionV1RequestDto
{
    /** @OA\Property(example="fa725671-bdce-43e6-8159-f37b748a667f") */
    public string $id;

    /** @OA\Property(example="expense") */
    public string $type;

    /** @OA\Property(example="100.5") */
    public string $amount;

    /** @OA\Property(example="f680553f-6b40-407d-a528-5123913be0aa") */
    public string $accountId;

    /** @OA\Property(example="monthly") */
    public string $interval;

    /** @OA\Property(example="2024-03-01 10:00:00") */
    public string $startDate;

    /** @OA\Property(example="Netflix") */
    public ?string $description = null;

    /** @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7") */
    public ?string $categoryId = null;

    /** @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7") */
    public ?string $payeeId = null;

    /** @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7") */
    public ?string $tagId = null;
}
