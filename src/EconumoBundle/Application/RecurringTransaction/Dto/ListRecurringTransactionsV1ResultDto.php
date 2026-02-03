<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class ListRecurringTransactionsV1ResultDto
{
    /**
     * @var RecurringTransactionResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
