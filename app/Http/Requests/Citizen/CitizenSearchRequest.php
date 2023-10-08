<?php

namespace App\Http\Requests\Citizen;

use App\Http\Requests\PaginatedRequest;

class CitizenSearchRequest extends PaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'lastname' => 'string',
                'firstname' => 'string',
                'birthdate' => 'date_format:Y-m-d'
            ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge(parent::messages(),
            [
                'lastname.string' => 'Lastname is a string!',
                'firstname.string' => 'Firstname is a string!',
                'birthdate.date_format' => 'Birthdate is a date at format YYYY-mm-dd!'
            ]);
    }
}
