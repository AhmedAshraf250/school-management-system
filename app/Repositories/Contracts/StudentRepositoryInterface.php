<?php

namespace App\Repositories\Contracts;

use App\Models\Image;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface StudentRepositoryInterface
{
    public function getAllStudents(): Collection;

    public function getById(int $id): Student;

    public function getClassroomsByGrade(int $gradeId);

    public function getSectionsByClassroom(int $classroomId);

    public function store(array $data): Student;

    public function update(array $data, int $id): Student;

    public function delete(int $id): void;

    public function uploadImages(array $images, Student $student): void;

    public function getStudentAttachment(Student $student, int $attachmentId): Image;

    public function deleteStudentAttachment(Student $student, int $attachmentId): void;
}
