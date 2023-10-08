<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|unique:roles,name',
            'ident' => 'required|string|unique:roles,ident',
            'description' => 'required|string',
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
            'name.required' => 'Name is required!',
            'name.string' => 'Name is a string!',
            'name.unique' => 'Name is unique!',
            'ident.required' => 'Ident is required!',
            'ident.string' => 'Ident is a string!',
            'ident.unique' => 'Ident is unique!',
            'description.required' => 'Description is required!',
            'description.string' => 'Description is a string!',
            'permissions.required' => 'Permissions is required!',
            'permissions.array' => 'Permissions is an array!',
            'permissions.*.integer' => 'Permissions is an array of integers!',
            'permissions.*.distinct' => 'Permissions must be different!',
            'permissions.*.exists' => 'Permission :input does not exist!'
        ];
    }
}
