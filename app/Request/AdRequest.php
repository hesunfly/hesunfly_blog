<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class AdRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'desc' => ['bail', 'required', 'string'],
            'url' => ['bail', 'sometimes', 'url'],
            'image_path' => ['bail', 'required'],
            'status' => ['bail', 'required', Rule::in([-1, 1])],
            'sort' => ['bail', 'required', 'numeric', 'max:200'],
        ];
    }
}
