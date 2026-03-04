<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeInvoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'student_id',
        'grade_id',
        'classroom_id',
        'fee_id',
        'amount',
        'description',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }
}
