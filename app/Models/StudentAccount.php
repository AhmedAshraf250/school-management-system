<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAccount extends Model
{
    protected $fillable = [
        'date',
        'type',
        'fee_invoice_id',
        'student_id',
        'grade_id',
        'classroom_id',
        'receipt_id',
        'processing_id',
        'payment_id',
        'debit',
        'credit',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function feeInvoice(): BelongsTo
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function processingFee(): BelongsTo
    {
        return $this->belongsTo(ProcessingFee::class, 'processing_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function scopeIncludedInTotals(Builder $query): Builder
    {
        /*
        WHERE (
                    (type='invoice' AND EXISTS fee_invoices row)
                    OR (type='receipt' AND EXISTS receipts row)
                    OR (type='payment' AND EXISTS payments row)
                    OR (type='processing_fee' AND EXISTS processing_fees row)
                    OR type NOT IN ('invoice','receipt','payment','processing_fee')
                )
        */
        return $query->where(function (Builder $query) {
            $query->where(function (Builder $typeQuery) {
                $typeQuery->where('type', 'invoice')
                    ->whereHas('feeInvoice');
            })->orWhere(function (Builder $typeQuery) {
                $typeQuery->where('type', 'receipt')
                    ->whereHas('receipt');
            })->orWhere(function (Builder $typeQuery) {
                $typeQuery->where('type', 'payment')
                    ->whereHas('payment');
            })->orWhere(function (Builder $typeQuery) {
                $typeQuery->where('type', 'processing_fee')
                    ->whereHas('processingFee');
            })->orWhereNotIn('type', ['invoice', 'receipt', 'payment', 'processing_fee']);
        });
    }
}
