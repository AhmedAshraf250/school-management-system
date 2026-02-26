<?php

namespace App\Repositories\Contracts;

interface StaticDataRepositoryInterface
{
    public function getGrades();

    public function getClassrooms();

    public function getSections();

    public function getGuardians();

    public function getGenders();

    public function getNationalities();

    public function getBloodTypes();
}
