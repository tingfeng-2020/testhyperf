<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class FooRequest extends FormRequest
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
            'foo' => 'required|min:2',
            'bar' => 'required',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'foo.required' => ':attribute 是必须的～',
            'bar.required'  => 'bar is required',
        ];
    }

    /**
     * 获取验证错误的自定义属性
     */
    public function attributes(): array
    {
        return [
            'foo' => 'foo:',
        ];
    }


}
