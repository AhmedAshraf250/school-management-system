<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundAccount extends Model
{
    protected $fillable = [
        'date',
        'receipt_id',
        'payment_id',
        'debit',
        'credit',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
