<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class AuthRequest extends FormRequest
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
            'name' => [
                'bail',
                'required',
                'string',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:6',
                'max:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute不能为空',
            'string' => ':attribute必须为字符类型',
            'email.email' => '邮箱格式不正确',
            'max:20' => ':attributes不能超过20个字符',
            'min:5' => ':attributes不能少于5个字符',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => '邮箱',
            'password' => '密码',
        ];
    }

}
