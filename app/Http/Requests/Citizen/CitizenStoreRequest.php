<?php

namespace App\Http\Requests\Citizen;

use App\Repositories\CitizenRepository;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CitizenStoreRequest extends FormRequest
{
    use ApiResponse;

    public CitizenRepository $citizenRepository;

    public function __construct(CitizenRepository $citizenRepository, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->citizenRepository = $citizenRepository;
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $rules = [
            'lastname' => ['required', 'string'],
            'firstname' => 'required|string',
            'gender' => 'in:m,f,nb',
            'birthdate' => 'date_format:Y-m-d',
            'force' => 'boolean'
        ];
        if (!$this['force'])
            $rules['lastname'] = array_merge($rules['lastname'],
                [Rule::unique('citizens')->where(function (Builder $query) {
                    $query->where('lastname', $this['lastname'])
                        ->where('firstname', $this['firstname']);
                    return $query;
                })->withoutTrashed()]);
        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'lastname.required' => 'Lastname is required!',
            'lastname.string' => 'Lastname is a string!',
            'lastname.unique' => "The combination of lastname $this->lastname, firstname $this->firstname and birthdate $this->birthdate already exists!",
            'firstname.required' => 'Firstname is required!',
            'firstname.string' => 'Firstname is a string!',
            'gender.required' => 'Gender is required!',
            'gender.in' => 'Gender must be m, f or nb',
            'birthdate.required' => 'Birthdate is required!',
            'birthdate.date_format' => 'Birthdate is a date at format YYYY-mm-dd!'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        foreach ($validator->failed() as $failed) {
            if (array_key_exists('Unique', $failed)) {
                $this->handleUniqueFailedValidation($validator);
            }
        }
        parent::failedValidation($validator);
    }

    private function handleUniqueFailedValidation(Validator $validator)
    {
        $organizations = $this->citizenRepository->strictSearch(array_merge($this->only(['lastname', 'firstname', 'birthdate']), ['all' => true]));
        $response = $this->sendResponse($organizations, 'List of potential citizens candidates', 409);
        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
