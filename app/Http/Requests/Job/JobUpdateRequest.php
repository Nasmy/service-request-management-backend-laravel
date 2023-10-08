<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => 'required_without_all:assigned_to_user,contact_id,start_date,end_date,complete_date,status,notes|string|min:3',
            'assigned_to_user' => [
                'required_without_all:subject,contact_id,start_date,end_date,complete_date,status,notes',
                'int',
                Rule::exists('users', 'id')
                    ->withoutTrashed()
            ],
            'contact_id' => [
                'required_without_all:subject,assigned_to_user,start_date,end_date,complete_date,status,notes',
                'int',
                Rule::exists('contacts', 'id')
                    ->withoutTrashed()
            ],
            'start_date' => 'required_without_all:subject,assigned_to_user,contact_id,end_date,complete_date,status,notes|date_format:Y-m-d',
            'end_date' => 'required_without_all:subject,assigned_to_user,contact_id,start_date,complete_date,status,notes|date_format:Y-m-d|after_or_equal:start_date',
            'complete_date' => 'required_without_all:subject,assigned_to_user,contact_id,start_date,end_date,status,notes|date_format:Y-m-d|after_or_equal:start_date',
            'status' => 'required_without_all:subject,assigned_to_user,contact_id,start_date,end_date,complete_date,notes|in:todo,in_progress,completed,archived',
            'notes' => 'required_without_all:subject,assigned_to_user,contact_id,start_date,end_date,complete_date,status|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Subject is required!',
            'subject.string' => 'Subject is a string!',
            'subject.unique' => 'Subject is unique!',
            'assigned_to_user.required' => 'The assigned to property is required!',
            'assigned_to_user.int' => 'The assigned to property must be a valid integer!',
            'assigned_to_user.exists' => 'The user :input does not exists!',
            'start_date.required' => 'Start date is required!',
            'start_date.date_format' => 'Start date is a date at format YYYY-mm-dd!',
            'start_date.after_or_equal' => 'Start date must be after or equal at today!',
            'end_date.required' => 'End date is required!',
            'end_date.date_format' => 'End date is a date at format YYYY-mm-dd!',
            'end_date.after_or_equal' => 'End date must be after or equal at start date!',
            'complete_date.required' => 'Complete date is required!',
            'complete_date.date_format' => 'Complete date is a date at format YYYY-mm-dd!',
            'complete_date.after_or_equal' => 'Complete date must be after or equal at start date!',
            'status.required' => 'Status is required!',
            'status.in' => 'Status value must be in todo, in_progress, completed or archived!',
            'notes.string' => 'Notes must be a string!'
        ];
    }
}
