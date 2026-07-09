<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsEvent extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'event_date',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'status' => 'boolean',
        ];
    }
}
