<?php

namespace App\Repositories\Eloquent;

use App\Models\Classroom;
use App\Models\Image;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\Concerns\HandlesMorphImageUploads;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentEloRepository implements StudentRepositoryInterface
{
    use HandlesMorphImageUploads;

    public function getAllStudents(): Collection
    {
        return Student::query()
            ->with([
                'gender:id,name',
                'nationality:id,name',
                'bloodType:id,name',
                'grade:id,Name',
                'classroom:id,name,grade_id',
                'section:id,name,grade_id,classroom_id',
                'guardian:id,father_name',
            ])
            ->latest()
            ->get();
    }

    public function getById(int $id): Student
    {
        return Student::query()->findOrFail($id);
    }

    public function getClassroomsByGrade(int $gradeId)
    {
        return Classroom::query()
            ->where('grade_id', $gradeId)
            ->get(['id', 'name'])
            ->mapWithKeys(fn(Classroom $classroom): array => [$classroom->id => $classroom->name]);
    }

    public function getSectionsByClassroom(int $classroomId)
    {
        return Section::query()
            ->where('classroom_id', $classroomId)
            ->get(['id', 'name'])
            ->mapWithKeys(fn(Section $section): array => [$section->id => $section->name]);
    }

    public function store(array $data): Student
    {
        return DB::transaction(function () use ($data): Student {
            $student = Student::query()->create([
                'name' => [
                    'en' => $data['name_en'],
                    'ar' => $data['name_ar'],
                ],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gender_id' => $data['gender_id'],
                'nationality_id' => $data['nationality_id'],
                'blood_id' => $data['blood_id'],
                'date_birth' => $data['date_birth'],
                'grade_id' => $data['grade_id'],
                'classroom_id' => $data['classroom_id'],
                'section_id' => $data['section_id'],
                'guardian_id' => $data['guardian_id'],
                'academic_year' => $data['academic_year'],
            ]);

            $this->storeMorphImages($student, $data['photos'] ?? [], 'attachments/students');

            return $student;
        });
    }

    public function update(array $data, int $id): Student
    {
        return DB::transaction(function () use ($data, $id): Student {
            $student = Student::query()->findOrFail($id);
            $student->email = $data['email'] ?? $student->email;
            $student->name = [
                'en' => $data['name_en'] ?? $student->getTranslation('name', 'en'),
                'ar' => $data['name_ar'] ?? $student->getTranslation('name', 'ar'),
            ];
            $student->gender_id = $data['gender_id'] ?? $student->gender_id;
            $student->nationality_id = $data['nationality_id'] ?? $student->nationality_id;
            $student->blood_id = $data['blood_id'] ?? $student->blood_id;
            $student->date_birth = $data['date_birth'] ?? $student->date_birth;
            $student->grade_id = $data['grade_id'] ?? $student->grade_id;
            $student->classroom_id = $data['classroom_id'] ?? $student->classroom_id;
            $student->section_id = $data['section_id'] ?? $student->section_id;
            $student->guardian_id = $data['guardian_id'] ?? $student->guardian_id;
            $student->academic_year = $data['academic_year'] ?? $student->academic_year;

            if (! empty($data['password'] ?? null)) {
                $student->password = Hash::make($data['password']);
            }

            $student->save();

            $this->storeMorphImages($student, $data['photos'] ?? [], 'attachments/students');

            return $student;
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $student = Student::query()->findOrFail($id);
            $this->deleteMorphImages($student);
            $student->delete();
        });
    }

    public function uploadImages(array $images, Student $student): void
    {
        $this->storeMorphImages($student, $images, 'attachments/students');
    }

    public function getStudentAttachment(Student $student, int $attachmentId): Image
    {
        return Image::query()
            ->whereKey($attachmentId)
            ->where('imageable_id', $student->id)
            ->where('imageable_type', Student::class)
            ->firstOrFail();
    }

    public function deleteStudentAttachment(Student $student, int $attachmentId): void
    {
        $attachment = $this->getStudentAttachment($student, $attachmentId);

        DB::transaction(function () use ($attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        });
    }
}
