<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends CrudModel
{
    use HasFactory;

    protected $table = 'organizations';

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'name'
        ]);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'organization_id');
    }
}
