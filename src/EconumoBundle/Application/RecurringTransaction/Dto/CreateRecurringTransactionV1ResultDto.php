<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\RecurringTransaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateRecurringTransactionV1ResultDto
{
    /**
     * @OA\Property()
     */
    public RecurringTransactionResultDto $item;
}
