<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Citizen extends CrudModel
{
    use HasFactory;

    protected $table = 'citizens';

    /**
     * @return string[]
     */
    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'lastname',
            'firstname',
            'gender',
            'birthdate',
        ]);
    }

    /**
     * @return string[]
     */
    public function getCasts(): array
    {
        return array_merge($this->casts, [
            'birthdate' => 'date:Y-m-d'
        ]);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'citizen_id');
    }
}
