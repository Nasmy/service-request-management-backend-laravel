<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CitizenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Carbon $birthdate */
        $birthdate = $this->birthdate;
        return array_merge(
            parent::toArray($request->except('birthdate')),
            [
                'birthdate' => $birthdate->toDateString(),
                'contacts' => ContactCollection::make($this->contacts)
            ]
        );
    }
}
