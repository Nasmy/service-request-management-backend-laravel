<?php

namespace App\Http\Requests\Job;

use App\Http\Requests\PaginatedRequest;

class JobIndexRequest extends PaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'created_by' => 'boolean',
            'assigned_to' => 'boolean'
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
