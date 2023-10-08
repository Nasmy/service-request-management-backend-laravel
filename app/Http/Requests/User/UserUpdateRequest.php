<?php

namespace App\Http\Requests\User;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
   use ApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required_without_all:,last_name,email,mobile,city,zip,address,role_id',
            'last_name' => 'required_without_all:first_name,email,mobile,city,zip,address,role_id',
            'email' => ['required_without_all:first_name,last_name,mobile,city,zip,address,role_id', Rule::unique('users')->ignore($this->user)],
            'mobile' => ['required_without_all:first_name,last_name,email,city,zip,address,role_id', Rule::unique('users')->ignore($this->user)],
            'city' => 'required_without_all:first_name,last_name,email,mobile,zip,address,role_id',
            'zip' => 'required_without_all:first_name,last_name,email,mobile,city,address,role_id',
            'address' => 'required_without_all:first_name,last_name,email,mobile,city,zip,role_id',
            'role_id' => 'required_without_all:first_name,last_name,email,mobile,city,zip,address'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email is Unique!',
            'first_name.required' => 'First Name is required!',
            'last_name.required' => 'Last Name is required!',
            'mobile.required' => 'Mobile is required',
          ];
    }
}
