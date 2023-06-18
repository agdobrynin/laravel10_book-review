<?php

namespace App\Http\Requests;

use App\Enums\BookFilterEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class BookFilterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|min:3',
            'filter' => ['nullable', new In(array_column(BookFilterEnum::cases(), 'name'))],
        ];
    }
}
