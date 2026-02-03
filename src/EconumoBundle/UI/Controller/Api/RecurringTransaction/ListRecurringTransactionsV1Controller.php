<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\RecurringTransaction;

use App\EconumoBundle\Application\RecurringTransaction\RecurringTransactionService;
use App\EconumoBundle\Application\RecurringTransaction\Dto\ListRecurringTransactionsV1ResultDto;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListRecurringTransactionsV1Controller extends AbstractController
{
    public function __construct(
        private readonly RecurringTransactionService $recurringTransactionService
    ) {
    }

    /**
     * List recurring transaction rules
     *
     * @OA\Tag(name="RecurringTransaction"),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *         type="object",
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/JsonResponseOk"),
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref=@Model(type=ListRecurringTransactionsV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     */
    #[Route(path: '/api/v1/recurring-transaction/list', name: 'api_recurring_transaction_list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        $items = $this->recurringTransactionService->listRecurringTransactions($user->getId());
        
        $result = new ListRecurringTransactionsV1ResultDto();
        $result->items = $items;

        return ResponseFactory::createOkResponse($request, $result);
    }
}
