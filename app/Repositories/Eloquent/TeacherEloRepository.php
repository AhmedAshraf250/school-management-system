<?php

namespace App\Repositories\Eloquent;

use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class TeacherEloRepository implements TeacherRepositoryInterface
{
    public function getAllTeachers(): Collection
    {
        return Teacher::query()
            ->with(['gender:id,name', 'specialization:id,name'])
            ->get();
    }

    public function findById(int $id): ?Teacher
    {
        return Teacher::query()
            ->with(['gender:id,name', 'specialization:id,name'])
            ->find($id);
    }

    public function getSpecializations(): Collection
    {
        return Specialization::query()->get();
    }

    public function getGenders(): Collection
    {
        return Gender::query()->get();
    }

    public function store(array $data): Teacher
    {
        return Teacher::query()->create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => ['en' => $data['name_en'], 'ar' => $data['name_ar']],
            'specialization_id' => $data['specialization_id'],
            'gender_id' => $data['gender_id'],
            'joining_date' => $data['joining_date'],
            'address' => $data['address'],
        ]);
    }

    public function edit(int $id): Teacher
    {
        return Teacher::query()
            ->with(['gender:id,name', 'specialization:id,name'])
            ->findOrFail($id);
    }

    public function update(array $data, int $id): Teacher
    {
        $teacher = Teacher::query()->findOrFail($id);
        $teacher->email = $data['email'] ?? $teacher->email;
        $teacher->name = [
            'en' => $data['name_en'] ?? $teacher->getTranslation('name', 'en'),
            'ar' => $data['name_ar'] ?? $teacher->getTranslation('name', 'ar'),
        ];
        $teacher->specialization_id = $data['specialization_id'] ?? $teacher->specialization_id;
        $teacher->gender_id = $data['gender_id'] ?? $teacher->gender_id;
        $teacher->joining_date = $data['joining_date'] ?? $teacher->joining_date;
        $teacher->address = $data['address'] ?? $teacher->address;

        if (! empty($data['password'] ?? null)) {
            $teacher->password = Hash::make($data['password']);
        }

        $teacher->save();

        return $teacher;
    }

    public function delete(int $id): void
    {
        Teacher::query()->findOrFail($id)->delete();
    }
}
