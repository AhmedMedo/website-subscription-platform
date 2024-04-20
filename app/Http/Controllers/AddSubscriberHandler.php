<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddWebsiteSubscriberRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use App\Services\WebsiteSubscriberService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/add-subscriber',
    description: 'Add new subscriber',
    requestBody: new OA\RequestBody('#/components/requestBodies/AddWebsiteSubscriberRequest'),

    tags: ['Website Subscriber'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Subscriber added successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Subscriber added successfully.')
                ]
            )
        )
    ]
)]
class AddSubscriberHandler extends Handler
{


    public function __construct(private readonly ConnectionService $connectionService,private readonly WebsiteSubscriberService $websiteSubscriberService)
    {
    }


    public function __invoke(AddWebsiteSubscriberRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->connectionService->beginTransaction();
        try {
            $this->websiteSubscriberService->addWebsiteSubscriber($request->validated());
            $this->connectionService->commit();
        } catch (Exception $exception) {
            $this->connectionService->rollBack();
            $this->errorResponse('Something went wrong. Please try again.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponseWithMessage(__('Subscriber added successfully.'));
    }
}
