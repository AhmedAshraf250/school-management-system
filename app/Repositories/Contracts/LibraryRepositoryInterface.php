<?php

namespace App\Repositories\Contracts;

use App\Models\Library;

interface LibraryRepositoryInterface
{
    public function getAllBooks();

    public function getById(int $id);

    public function getClassroomsByGrade(int $gradeId);

    public function getSectionsByClassroom(int $classroomId);

    public function store(array $data);

    public function update(array $data, Library $library);

    public function delete(Library $library);
}
