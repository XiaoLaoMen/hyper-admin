<?php

declare(strict_types=1);

namespace App\Request\Admin;

use Hyperf\Validation\Request\FormRequest;

class LoginRequest extends FormRequest
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
            'emailormob' =>'required|max:60',
            'password' =>'required|max:60',
            'captcha'=>'required|max:60',
        ];
    }

    public function attributes(): array
    {
        return [
            'emailormob' =>'手机或邮箱',
            'password' =>'密码',
            'captcha' => '验证码',
        ];
    }
}
