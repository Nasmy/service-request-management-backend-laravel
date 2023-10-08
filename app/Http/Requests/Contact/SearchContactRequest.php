<?php

namespace App\Http\Requests\Contact;

use App\Http\Requests\PaginatedRequest;

class SearchContactRequest extends PaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'email' => 'email',
                'mobile' => 'max:50',
                'phone' => 'max:50',
                'address' => 'max:200',
                'city' => 'string|max:100',
                'zip' => 'numeric',
            ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
