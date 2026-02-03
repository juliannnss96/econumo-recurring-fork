<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\RecurringTransaction;

use App\EconumoBundle\Application\RecurringTransaction\RecurringTransactionService;
use App\EconumoBundle\Application\RecurringTransaction\Dto\CreateRecurringTransactionV1RequestDto;
use App\EconumoBundle\Application\RecurringTransaction\Dto\CreateRecurringTransactionV1ResultDto;
use App\EconumoBundle\UI\Controller\Api\RecurringTransaction\Validation\CreateRecurringTransactionV1Form;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use App\EconumoBundle\UI\Service\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateRecurringTransactionV1Controller extends AbstractController
{
    public function __construct(
        private readonly RecurringTransactionService $recurringTransactionService,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * Create a recurring transaction rule
     *
     * @OA\Tag(name="RecurringTransaction"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=CreateRecurringTransactionV1RequestDto::class))),
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
     *                     ref=@Model(type=CreateRecurringTransactionV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     */
    #[Route(path: '/api/v1/recurring-transaction/create', name: 'api_recurring_transaction_create', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new CreateRecurringTransactionV1RequestDto();
        $this->validator->validate(CreateRecurringTransactionV1Form::class, $request->request->all(), $dto);
        
        $user = $this->getUser();
        $result = $this->recurringTransactionService->createRecurringTransaction($dto, $user->getId());
        
        $responseDto = new CreateRecurringTransactionV1ResultDto();
        $responseDto->item = $result;

        return ResponseFactory::createOkResponse($request, $responseDto);
    }
}
