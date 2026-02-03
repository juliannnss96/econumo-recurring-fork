<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "type", "accountId", "amount", "description", "interval", "nextRunDate", "isActive"}
 * )
 */
class RecurringTransactionResultDto
{
    /**
     * Id
     * @var string
     * @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public string $id;

    /**
     * Transaction type
     * @OA\Property(example="expense")
     */
    public string $type;

    /**
     * Account id
     * @OA\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $accountId;

    /**
     * Amount
     * @OA\Property(example="100.5")
     */
    public string $amount;

    /**
     * Category id
     * @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public ?string $categoryId = null;

    /**
     * Description
     * @OA\Property(example="Netflix")
     */
    public string $description;

    /**
     * Payee id
     * @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public ?string $payeeId = null;

    /**
     * Tag id
     * @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public ?string $tagId = null;

    /**
     * Interval
     * @OA\Property(example="monthly")
     */
    public string $interval;

    /**
     * Next run date
     * @OA\Property(example="2024-03-01 10:00:00")
     */
    public string $nextRunDate;

    /**
     * Is active
     * @OA\Property(example=true)
     */
    public bool $isActive;
}
