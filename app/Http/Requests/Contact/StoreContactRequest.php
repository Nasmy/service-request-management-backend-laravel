<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
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
            'email' => 'required_without_all:mobile,phone,address|email|nullable',
            'mobile' => 'required_without_all:email,phone,address|max:50|nullable',
            'phone' => 'required_without_all:email,mobile,address|max:50|nullable',
            'address' => 'required_without_all:email,mobile,phone|min:2|max:200|nullable',
            'city' => 'required_with:address|string|min:2|max:100|nullable',
            'zip' => 'required_with:address|numeric|nullable',
            'position' => 'string|min:2|max:200|nullable',
            'citizen_id' => [
                'required',
                Rule::exists('citizens', 'id')
                    ->withoutTrashed(),
                'int'
            ],
            'organization_id' => [
                Rule::exists('organizations', 'id')
                    ->withoutTrashed(),
                'int',
                'nullable'
            ],
        ];
    }
}
