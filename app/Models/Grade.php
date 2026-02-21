<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Grade extends Model
{
    use HasTranslations;

    public $translatable = ['Name'];

    protected $fillable = ['Name', 'Notes'];

    protected $table = 'grades';

    public $timestamps = true;

    /* RELATIONS */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'grade_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'grade_id');
    }
}
