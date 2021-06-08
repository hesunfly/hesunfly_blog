<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class ArticleRequest extends FormRequest
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
                    'category_id' => ['bail', 'required', 'checkArticleCategory'],
                    'description' => ['bail', 'required', 'string'],
                    'slug' => ['unique:article'],
                    'status' => ['bail', 'required', Rule::in([-1, 1])],
                    'content' => ['bail', 'required', 'string'],
                    'html_content' => ['bail', 'required', 'string'],
                ];

            case 'PUT':
                return [
                    'title' => ['bail', 'required', 'string', Rule::unique('article')->ignore($this->id)],
                    'category_id' => ['bail', 'required', 'CheckArticleCategory'],
                    'description' => ['bail', 'required', 'string'],
                    'slug' => [Rule::unique('article')->where(function ($query) {
                        $query->where('id', $this->input('id'));
                    })],
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
