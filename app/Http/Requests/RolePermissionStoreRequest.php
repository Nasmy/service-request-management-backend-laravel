<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolePermissionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'permissions' => 'required|array',
            'permissions.*' => [
                'integer',
                'distinct',
                Rule::exists('permissions', 'id')
                    ->withoutTrashed()
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => 'Permissions is required!',
            'permissions.array' => 'Permissions is an array!',
            'permissions.*.integer' => 'Permissions is an array of integers!',
            'permissions.*.distinct' => 'Permissions must be different!',
            'permissions.*.exists' => 'Permission :input does not exist!'
        ];
    }
}
