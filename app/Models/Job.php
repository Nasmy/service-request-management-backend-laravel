<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends CrudModel
{
    use HasFactory;

    protected $table = 'jobs';

    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'subject',
            'contact_id',
            'assigned_to_user',
            'start_date',
            'end_date',
            'complete_date',
            'status',
            'notes',
        ]);
    }

    public function getHidden(): array
    {
        return array_merge(parent::getHidden(), [
            'contact_id',
            'assigned_to_user'
        ]);
    }

    public function getCasts(): array
    {
        return array_merge($this->casts, [
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'complete_date' => 'date:Y-m-d',
            'assigned_to_user' => 'integer',
            'citizen_id' => 'integer'
        ]);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'job_id');
    }
}
