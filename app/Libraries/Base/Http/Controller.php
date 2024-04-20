<?php

namespace App\Libraries\Base\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use League\Fractal;
use League\Fractal\Resource\ResourceInterface;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", title: "Subscription Platform documentation")]
#[OA\Schema(
    schema: 'Meta',
    properties: [
        new OA\Property(property: 'total', type: 'integer'),
        new OA\Property(property: 'per_page', type: 'integer'),
        new OA\Property(property: 'current_page', type: 'integer'),
        new OA\Property(property: 'last_page', type: 'integer'),
        new OA\Property(property: 'from', type: 'integer'),
        new OA\Property(property: 'to', type: 'integer'),
    ],
    type: 'object'
)]

abstract class Controller extends \Illuminate\Routing\Controller
{
    /**
     * @param ResourceInterface $resource
     * @param string[] $includes
     * @param int $status
     * @return JsonResponse
     */
    public function successResponse(Fractal\Resource\ResourceInterface $resource, array $includes = [], int $status = 200): JsonResponse
    {
        return response()->json(array_merge(['status' => 'success'], $this->toArray($resource, $includes)), $status);
    }

    /**
     * @param Fractal\Resource\ResourceInterface $resource
     * @param string[] $includes
     *
     * @return array
     */
    public function toArray(Fractal\Resource\ResourceInterface $resource, array $includes = []): array
    {
        $manager = new Fractal\Manager();
        $manager->setSerializer($this->responseSerializer());

        if (count($includes)) {
            $manager->parseIncludes($includes);
        }

        return $manager->createData($resource)->toArray();
    }

    protected function responseSerializer(): Fractal\Serializer\SerializerAbstract
    {
        return new Fractal\Serializer\DataArraySerializer();
    }

    public function successResponseWithMessage(string $message = 'OK'): JsonResponse
    {
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * @param null $data
     * @param string $message
     * @return JsonResponse
     */
    public function successResponseWithData($data = null, $message = 'Request Success'): JsonResponse
    {
        return response()->json(['status' => 'success','message' => $message, 'data' => $data]);
    }

    public function successResponseWithDataAndMeta(array $data = [], array $meta = [], $message = 'Request Success'): JsonResponse
    {
        return response()->json(['status' => 'success','message' => $message, 'data' => $data, 'meta' => $meta]);
    }

    /**
     * @param array  $data
     * @param int    $status
     * @param string $message
     *
     * @return JsonResponse
     */
    public function errorResponseWithData(array $data = [], string $message = 'Error.', int $status = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * @param mixed $value
     *
     * @return JsonResponse
     */
    public function successResponseWithValue($value): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $value], 200);
    }

    public function errorResponse(string $message, int $status = 200): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message, 'error' => $message], $status);
    }

    /**
     * @param array $data
     * @param array $headers
     * @param int $response
     *
     * @return JsonResponse
     */
    public function respond(array $data = [], array $headers = [], int $response = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $response, $headers);
    }
}
