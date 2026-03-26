<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class onlineClass extends Model
{
    protected $fillable = [
        'integration',
        'grade_id',
        'classroom_id',
        'section_id',
        'created_by',
        'meeting_id',
        'topic',
        'start_at',
        'duration',
        'password',
        'start_url',
        'join_url',
    ];

    // --------[Relations]-------- //
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

    public function teacherCreator(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by', 'email');
    }

    public function adminCreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'email');
    }

    public function getCreatorDisplayNameAttribute(): string
    {
        if ($this->teacherCreator instanceof Teacher) {
            return $this->teacherCreator->name;
        }

        if ($this->adminCreator instanceof User) {
            return (string) $this->adminCreator->name;
        }

        return (string) ($this->created_by ?? trans('main_trans.dashboard_no_data'));
    }
}
