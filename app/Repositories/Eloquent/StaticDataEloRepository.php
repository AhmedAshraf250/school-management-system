<?php

namespace App\Repositories\Eloquent;

use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Section;
use App\Repositories\Contracts\StaticDataRepositoryInterface;

class StaticDataEloRepository implements StaticDataRepositoryInterface
{
    public function getGrades()
    {
        return Grade::query()->get();
    }

    public function getClassrooms()
    {
        return Classroom::query()->get();
    }

    public function getSections()
    {
        return Section::query()->get();
    }

    public function getGuardians()
    {
        return Guardian::query()->get();
    }

    public function getGenders()
    {
        return Gender::query()->get();
    }

    public function getNationalities()
    {
        return Nationality::query()->get();
    }

    public function getBloodTypes()
    {
        return \App\Models\BloodType::query()->get();
    }
}
