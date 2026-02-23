<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuardianAttachment extends Model
{
    protected $fillable = ['file_name', 'guardian_id'];
}
