<?php

namespace App\Repositories\Eloquent;

use App\Models\Classroom;
use App\Models\Library;
use App\Models\Section;
use App\Repositories\Concerns\HandlesAttachmentUploads;
use App\Repositories\Contracts\LibraryRepositoryInterface;
use Illuminate\Support\Str;
use Throwable;

class LibraryEloRepository implements LibraryRepositoryInterface
{
    use HandlesAttachmentUploads;

    public function getAllBooks()
    {
        return Library::query()
            ->with([
                'teacher:id,name',
                'grade:id,Name',
                'classroom:id,name',
                'section:id,name',
            ])
            ->latest()
            ->get();
    }

    public function getById(int $id)
    {
        return Library::query()->findOrFail($id);
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

    public function store(array $data): Library
    {
        $fileData = $this->storeAttachment($data['file'], 'attachments/libraries');

        try {
            return Library::query()->create([
                'title' => $data['title'],
                'file_name' => $fileData['file_name'],
                'path' => $fileData['path'],
                'grade_id' => $data['grade_id'],
                'classroom_id' => $data['classroom_id'],
                'section_id' => $data['section_id'],
                'teacher_id' => $data['teacher_id'],
            ]);
        } catch (Throwable $exception) {
            $this->deleteAttachment($fileData['path']);

            throw $exception;
        }
    }

    public function update(array $data, Library $library)
    {
        $library->title = $data['title'];
        $library->grade_id = $data['grade_id'];
        $library->classroom_id = $data['classroom_id'];
        $library->section_id = $data['section_id'];
        $library->teacher_id = $data['teacher_id'];

        $file = $data['file'] ?? null;
        $oldPath = $library->path;
        $newPath = null;

        if ($file !== null) {
            $fileData = $this->storeAttachment($file, 'attachments/libraries');
            $library->file_name = $fileData['file_name'];
            $library->path = $fileData['path'];
            $newPath = $fileData['path'];
        }

        try {
            $library->save();
        } catch (Throwable $exception) {
            if ($newPath !== null) {
                $this->deleteAttachment($newPath);
            }

            throw $exception;
        }

        if ($newPath !== null) {
            $this->deleteAttachment($oldPath);
        }

        return $library;
    }

    public function delete(Library $library)
    {
        $originalPath = $library->path;
        $temporaryPath = null;

        if (! empty($originalPath)) {
            $temporaryPath = 'attachments/libraries/.trash/' . Str::uuid()->toString() . '_' . basename($originalPath);
            $this->moveAttachment($originalPath, $temporaryPath);
        }

        try {
            $library->delete();
        } catch (Throwable $exception) {
            if ($temporaryPath !== null) {
                $this->moveAttachment($temporaryPath, $originalPath);
            }

            throw $exception;
        }

        if ($temporaryPath !== null) {
            $this->deleteAttachment($temporaryPath);
        }
    }
}
