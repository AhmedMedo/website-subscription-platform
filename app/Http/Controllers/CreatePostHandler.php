<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use App\Services\PostService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/post',
    description: 'Add new Post',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreatePostRequest'),

    tags: ['Posts'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Post created successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Post created successfully.')
                ]
            )
        )
    ]
)]

class CreatePostHandler extends Handler
{

    public function __construct(private readonly ConnectionService $connectionService, private readonly PostService $postService)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreatePostRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->connectionService->beginTransaction();
        try {
            $this->postService->createPost($request->validated(), $request->file('thumbnail'));
            $this->connectionService->commit();
        } catch (Exception $exception) {
            $this->connectionService->rollBack();
            $this->errorResponse('Something went wrong. Please try again.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponseWithMessage(__('Post created successfully.'));
    }

}
