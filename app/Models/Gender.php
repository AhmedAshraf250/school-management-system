<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Gender extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name'];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class, 'gender_id');
    }
}
