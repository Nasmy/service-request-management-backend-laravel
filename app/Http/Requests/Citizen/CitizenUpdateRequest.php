<?php

namespace App\Http\Requests\Citizen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CitizenUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lastname' => [
                'required_without_all:firstname,gender,birthdate',
                'string',
                Rule::unique('citizens')->where(function ($query) {
                    return $query->where('lastname', $this['lastname'])
                        ->where('firstname', $this['firstname'])
                        ->where('birthdate', $this['birthdate']);
                })->ignore($this->route('citizen'))->withoutTrashed()
            ],
            'firstname' => 'required_without_all:lastname,gender,birthdate|string',
            'gender' => 'required_without_all:lastname,firstname,birthdate|in:m,f,nb',
            'birthdate' => 'required_without_all:lastname,firstname,gender|date_format:Y-m-d'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'lastname.required' => 'Lastname is required!',
            'lastname.string' => 'Lastname is a string!',
            'lastname.unique' => "The combination of lastname $this->lastname, firstname $this->firstname and birthdate $this->birthdate already exists!",
            'firstname.required' => 'Firstname is required!',
            'firstname.string' => 'Firstname is a string!',
            'gender.required' => 'Gender is required!',
            'gender.in' => 'Gender must be m, f or nb',
            'birthdate.required' => 'Birthdate is required!',
            'birthdate.date_format' => 'Birthdate is a date at format YYYY-mm-dd!'
        ];
    }
}
