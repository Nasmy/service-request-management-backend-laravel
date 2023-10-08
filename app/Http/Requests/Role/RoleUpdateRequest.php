<?php

namespace App\Http\Requests\Role;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
{
    use ApiResponse;

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
            'name' => "required_without_all:ident,description,active,permissions|string|unique:roles,name,{$this->route('role')}",
            'ident' => "required_without_all:name,description,active,permissions|string|unique:roles,ident,{$this->route('role')}",
            'description' => 'required_without_all:name,ident,active,permissions|string',
            'active' => 'required_without_all:name,ident,description,permissions|boolean',
            'permissions' => 'required_without_all:name,ident,description,active|array',
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
            'active.required' => 'Active is required!',
            'active.boolean' => 'Active is a boolean!',
            'permissions.required' => 'Permissions is required!',
            'permissions.array' => 'Permissions is an array!',
            'permissions.*.integer' => 'Permissions is an array of integers!',
            'permissions.*.distinct' => 'Permissions must be different!',
            'permissions.*.exists' => 'Permission :input does not exist!'
        ];
    }
}
