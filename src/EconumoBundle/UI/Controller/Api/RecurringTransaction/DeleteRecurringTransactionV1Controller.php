<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\RecurringTransaction;

use App\EconumoBundle\Application\RecurringTransaction\RecurringTransactionService;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteRecurringTransactionV1Controller extends AbstractController
{
    public function __construct(
        private readonly RecurringTransactionService $recurringTransactionService
    ) {
    }

    /**
     * Delete a recurring transaction rule
     *
     * @OA\Tag(name="RecurringTransaction"),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/JsonResponseOk")
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     */
    #[Route(path: '/api/v1/recurring-transaction/{id}', name: 'api_recurring_transaction_delete', methods: ['DELETE'])]
    public function __invoke(Request $request, string $id): Response
    {
        $recurringId = new Id($id);
        $recurring = $this->recurringTransactionService->getRecurringTransaction($recurringId);
        
        // Security check: ensure the recurring transaction belongs to the user
        if ($recurring->getUser()->getId()->getValue() !== $this->getUser()->getId()->getValue()) {
            throw $this->createAccessDeniedException('You do not have access to this recurring transaction.');
        }

        $this->recurringTransactionService->deleteRecurringTransaction($recurring);

        return ResponseFactory::createOkResponse($request);
    }
}
