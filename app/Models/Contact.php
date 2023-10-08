<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends CrudModel
{
    use HasFactory;

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'email',
            'mobile',
            'phone',
            'city',
            'zip',
            'address',
            'position',
            'organization_id',
            'citizen_id',
        ]);
    }

    public function getHidden(): array
    {
        return array_merge($this->hidden, [
            'organization_id',
            'citizen_id'
        ]);
    }

    public function getCasts(): array
    {
        return array_merge($this->casts, [
            'organization_id' => 'integer',
            'citizen_id' => 'integer'
        ]);
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizen_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'contact_id');
    }
}
