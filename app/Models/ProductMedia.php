<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductMedia extends Model
{
    protected $table = 'product_media';

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'type',
        'disk',
        'path',
        'file_name',
        'mime_type',
        'size',
        'sort_order',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): string
    {
        return \Illuminate\Support\Facades\Storage::disk($this->disk)->url($this->path);
    }
}
