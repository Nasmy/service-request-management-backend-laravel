<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
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
            'email' => 'required_without_all:mobile,phone,address,city,zip|email|nullable',
            'mobile' => 'required_without_all:email,phone,address,city,zip|max:50|nullable',
            'phone' => 'required_without_all:email,mobile,address, city,zip|max:50|nullable',
            'address' => 'required_without_all:email,mobile,phone,city,zip|string|min:2|max:200|nullable',
            'city' => 'required_without_all:email,mobile,phone,address,zip|string|min:2|max:100|nullable',
            'zip' => 'required_without_all:email,mobile,phone,address,city|numeric|nullable',
            'position' => '|string|min:2|max:200|nullable',
            'citizen_id' => [
                Rule::exists('citizens', 'id')
                    ->withoutTrashed(),
                'int'
            ],
            'organization_id' => [
                Rule::exists('organizations', 'id')
                    ->withoutTrashed(),
                'int',
                'nullable'
            ]
        ];
    }
}
