<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class CategoryRequest extends FormRequest
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
            'title' => [
                'bail',
                'required',
                'string',
                'unique:category'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute不能为空',
            'string' => ':attribute必须为字符类型',
            'unique' => '分类名已存在',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => '分类名称',
        ];
    }

}
