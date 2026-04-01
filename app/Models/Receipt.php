<?php

namespace App\Models;

use App\Models\Concerns\PreventsForceDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use PreventsForceDelete;
    use SoftDeletes;

    protected $fillable = [
        'date',
        'student_id',
        'debit',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
