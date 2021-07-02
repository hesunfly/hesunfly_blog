<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class SubscribeRequest extends FormRequest
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
            case 'PUT':
                return [
                    'status' => ['bail', 'required', Rule::in([-1, 1])],
                    'id' => ['required'],
                ];
            case 'POST':
                return [
                    'email' => [
                        'bail',
                        'required',
                        'email',
                    ]
                ];
            case 'GET':
                return [
                    'email' => ['bail', 'required', 'email'],
                    'key' => ['required'],
                    'code' => ['required'],
                ];
        }
    }

    public function messages(): array
    {
        return [
            'email.email' => '请输入正确的邮箱地址！',
            'email.unique' => '您已订阅，请勿重复订阅！',
        ];
    }
}
