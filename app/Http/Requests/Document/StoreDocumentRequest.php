<?php

namespace App\Http\Requests\Document;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
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
            'documents' => 'required',
            'documents.*' => [
                'required',
                'file',
                'mimes:' . join(',', Document::ALLOWED_MIMES),
                'max:' . config('app.upload.max_filesize')
            ],
            'job_id' => [
                'required',
                Rule::exists('jobs', 'id')
                    ->withoutTrashed()
            ]
        ];
    }
}
