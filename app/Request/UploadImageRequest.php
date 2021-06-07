<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class UploadImageRequest extends FormRequest
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
            'image' => [
                'bail',
                'required',
                'image',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute 不能为空',
            'image' => ':attribute 不是图片',
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => '文件',
        ];
    }

}
