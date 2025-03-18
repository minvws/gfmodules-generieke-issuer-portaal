<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\BsnRule;
use Illuminate\Foundation\Http\FormRequest;

class FlowCredentialDataRequest extends FormRequest
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
    public function rules(BsnRule $rule): array
    {
        return [
            'bsn' => ['required', 'min:8', 'max:9', $rule],
//            'birthyear' => ['required'],
            'consent' => ['required', 'accepted'],
        ];
    }
}
