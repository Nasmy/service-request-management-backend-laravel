<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends CrudModel
{
    use HasFactory;

    const ALLOWED_MIMES = [
        'doc',
        'pdf',
        'docx',
        'jpg',
        'jpeg',
        'png'
    ];


    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'path',
            'job_id',
            'type',
            'name'
        ]);
    }

    public function getHidden(): array
    {
        return array_merge($this->hidden, [
            'job_id'
        ]);
    }

    public function getPathAttribute($value)
    {
        if (!isset($value)) {
            return;
        }

        $url = filter_var($value, FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // return Storage::temporaryUrl($value, now()->addHours(1));
        return Storage::url($value);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
