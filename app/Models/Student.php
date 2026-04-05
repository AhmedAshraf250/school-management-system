<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Translatable\HasTranslations;

class Student extends Authenticatable
{
    use HasFactory;
    use HasTranslations;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_GRADUATED = 'graduated';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender_id',
        'nationality_id',
        'blood_id',
        'date_birth',
        'grade_id',
        'classroom_id',
        'section_id',
        'guardian_id',
        'academic_year',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'date_birth' => 'date',
        ];
    }

    // ==============[Relationships]============== //
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    public function bloodType(): BelongsTo
    {
        return $this->belongsTo(BloodType::class, 'blood_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function graduations(): HasMany
    {
        return $this->hasMany(Graduation::class, 'student_id');
    }

    public function student_account(): HasMany
    {
        return $this->studentAccountRelation();
    }

    private function studentAccountRelation(): HasMany
    {
        return $this->hasMany(StudentAccount::class, 'student_id')
            ->includedInTotals();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function todayAttendance(): HasOne
    {
        return $this->hasOne(Attendance::class, 'student_id')->today();
    }

    // ===== [SCOOPES] ===== //
    public function scopeWithAttendanceInSection(Builder $query, int $sectionId): Builder
    {
        return $query->where('section_id', $sectionId)
            ->with(['attendances' => function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            }]);
    }
}
