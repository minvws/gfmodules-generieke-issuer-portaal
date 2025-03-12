<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\DataDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlowAuthorizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'information_types' => ['required', 'array', Rule::in(DataDomain::cases())],
            'access_code' => ['required', 'string'],
        ];
    }
}
