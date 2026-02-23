<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Guardian extends Model
{
    use HasTranslations;

    public $translatable = ['father_name', 'father_job', 'mother_name', 'mother_job'];

    protected $fillable = [
        'email',
        'password',
        'father_name',
        'father_national_id',
        'father_passport_id',
        'father_phone',
        'father_job',
        'father_nationality_id',
        'father_blood_type_id',
        'father_religion_id',
        'father_address',
        'mother_name',
        'mother_national_id',
        'mother_passport_id',
        'mother_phone',
        'mother_job',
        'mother_nationality_id',
        'mother_blood_type_id',
        'mother_address',
    ];

    // ---- [Relations] ---- //
    public function fatherBloodType()
    {
        return $this->belongsTo(BloodType::class, 'father_blood_type_id');
    }

    public function motherBloodType()
    {
        return $this->belongsTo(BloodType::class, 'mother_blood_type_id');
    }

    //
    public function fatherReligion()
    {
        return $this->belongsTo(Religion::class, 'father_religion_id');
    }

    public function motherReligion()
    {
        return $this->belongsTo(Religion::class, 'mother_religion_id');
    }

    //
    public function fatherNational()
    {
        return $this->belongsTo(Nationality::class, 'father_nationality_id');
    }

    public function motherNational()
    {
        return $this->belongsTo(Nationality::class, 'mother_nationality_id');
    }
}
