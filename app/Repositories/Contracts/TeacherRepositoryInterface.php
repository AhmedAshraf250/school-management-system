<?php

namespace App\Repositories\Contracts;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;

interface TeacherRepositoryInterface
{
    public function getAllTeachers(): Collection;

    public function findById(int $id): ?Teacher;

    public function getSpecializations(): Collection;

    public function getGenders(): Collection;

    public function store(array $data): Teacher;

    public function edit(int $id): Teacher;

    public function update(array $data, int $id): Teacher;

    public function delete(int $id): void;
}
