<?php

declare(strict_types=1);

namespace App\Request\Admin;

use Hyperf\Validation\Request\FormRequest;

class MenuRequest extends FormRequest
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
            'pid' =>'required|integer|min:0',
            'name' =>'required|max:60',
            'url'=>'max:60',
            'icon'=>'max:60',
            'sort'=>'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'pid' => '上级',
            'name' => '权限名称',
            'url' => '菜单url',
            'icon' => '图标',
            'sort' => '排序',
        ];
    }
}
