<?php

namespace App\Http\Requests;

use App\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request: 'CreatePostRequest',
    required: true,
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'title',
                description: 'The title of the post',
                type: 'string',
                nullable: false,
            ),
            new OA\Property(
                property: 'description',
                description: 'The description of the post',
                type: 'string',
                nullable: false
            ),
            new OA\Property(
                property: 'thumbnail',
                description: 'The thumbnail of the post',
                type: 'file',
                nullable: true
            ),
            new OA\Property(
                property: 'website_id',
                description: 'The id of the website',
                type: 'integer',
                nullable: false
            ),
            new OA\Property(
                property: 'status',
                description: 'The status of the post',
                type: 'string',
                nullable: false
            )
        ]
    ),
)]

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'website_id' => ['required', 'exists:websites,id'],
            'status' => ['required', 'string', 'in:'.StatusEnum::toString()],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }
}
