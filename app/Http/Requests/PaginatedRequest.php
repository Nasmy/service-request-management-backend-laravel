<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedRequest extends FormRequest
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
            'per_page' => $this->has('all') ? '' : 'nullable|integer|min:1',
            'page' => $this->has('all') ? '' : 'nullable|integer|min:1',
            'all' => 'nullable|boolean'
        ];
    }
}
