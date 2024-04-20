<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request: 'AddWebsiteSubscriberRequest',
    required: true,
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'website_id',
                description: 'The id of the website',
                type: 'integer',
            ),
            new OA\Property(
                property: 'name',
                description: 'The name of the subscriber',
                type: 'string',
            ),
            new OA\Property(
                property: 'email',
                description: 'The email of the subscriber',
                type: 'string',
            )
        ]
    )
)]
class AddWebsiteSubscriberRequest extends FormRequest
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
            'website_id' => ['required', 'exists:websites,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:website_subscribers,email'],
        ];
    }
}
