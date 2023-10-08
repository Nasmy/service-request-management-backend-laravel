<?php

namespace App\Http\Requests\Organization;

use App\Repositories\OrganizationRepository;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreOrganizationRequest extends FormRequest
{
    use ApiResponse;

    public OrganizationRepository $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->organizationRepository = $organizationRepository;
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'name' => [
                'required',
                Rule::unique('organizations')
                    ->where('name', $this['name'])
                    ->withoutTrashed(),
                'min:3',
                'max:255'
            ]
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
        $organizations = $this->organizationRepository->search(array_merge($this->only(['name']), ['all' => true]));
        $response = $this->sendResponse($organizations, 'List of potential organizations candidates', 409);
        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
