<?php

namespace App\Http\Requests\Job;

class JobGlobalSearchRequest extends JobIndexRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'search' => 'string'
            ]
        );
    }

    public function authorize(): bool
    {
        return true;
    }
}
