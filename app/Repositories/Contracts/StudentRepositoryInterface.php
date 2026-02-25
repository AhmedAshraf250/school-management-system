<?php

namespace App\Repositories\Contracts;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface StudentRepositoryInterface
{
    public function getAllStudents(): Collection;

    public function getGrades(): Collection;

    public function getGuardians(): Collection;

    public function getGenders(): Collection;

    public function getNationalities(): Collection;

    public function getBloodTypes(): Collection;

    public function getClassroomsByGrade(int $gradeId): SupportCollection;

    public function getSectionsByClassroom(int $classroomId): SupportCollection;

    public function store(array $data): Student;

    public function edit(int $id): Student;

    public function update(array $data, int $id): Student;

    public function delete(int $id): void;
}
