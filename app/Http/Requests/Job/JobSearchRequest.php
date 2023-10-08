<?php

namespace App\Http\Requests\Job;

class JobSearchRequest extends JobIndexRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'subject' => 'string|min:3',
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d',
            'status' => 'in:todo,in_progress,completed,archived',
            'notes' => 'string'
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
