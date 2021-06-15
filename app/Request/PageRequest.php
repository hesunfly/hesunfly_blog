<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class PageRequest extends FormRequest
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
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'title' => ['bail', 'required', 'string', 'unique:article'],
                    'slug' => ['unique:page'],
                    'status' => ['bail', 'required', Rule::in([-1, 1])],
                    'content' => ['bail', 'required', 'string'],
                    'html_content' => ['bail', 'required', 'string'],
                ];

            case 'PUT':
                $id = $this->input('id');
                return [
                    'title' => ['bail', 'required', 'string', Rule::unique('page')->ignore($id)],
                    'slug' => [Rule::unique('page')->ignore($id)],
                    'status' => ['bail', 'required', Rule::in([-1, 1])],
                    'content' => ['bail', 'required', 'string'],
                    'html_content' => ['bail', 'required', 'string'],
                ];
            case 'PATCH':
                return [
                    'status' => ['bail', 'required', Rule::in([-1, 1])],
                ];
        }

    }
}
