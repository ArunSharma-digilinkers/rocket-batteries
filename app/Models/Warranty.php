<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'mobile',
        'email',
        'serial_no',
        'purchase_date',
        'dealer_name',
        'invoice_path',
        'status',
        'verified_at',
        'verified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'verified_by');
    }
}
