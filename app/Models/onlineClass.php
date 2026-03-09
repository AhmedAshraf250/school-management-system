<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class onlineClass extends Model
{
    public $fillable = [
        'integration',
        'grade_id',
        'classroom_id',
        'section_id',
        'user_id',
        'meeting_id',
        'topic',
        'start_at',
        'duration',
        'password',
        'start_url',
        'join_url',
    ];

    // --------[Relations]-------- //
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
