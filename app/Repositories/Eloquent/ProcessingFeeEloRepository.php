<?php

namespace App\Repositories\Eloquent;

use App\Models\ProcessingFee;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Repositories\Contracts\ProcessingFeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProcessingFeeEloRepository implements ProcessingFeeRepositoryInterface
{
    public function all(): Collection
    {
        return ProcessingFee::with('student:id,name')->orderByDesc('date')->get();
    }

    public function find(int $id): ProcessingFee
    {
        return ProcessingFee::with('student')->findOrFail($id);
    }

    public function getStudentWithAccount(int $studentId): Student
    {
        return Student::with('student_account')->findOrFail($studentId);
    }

    public function createProcessingFee(array $data): ProcessingFee
    {
        return DB::transaction(function () use ($data) {
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];

            $fee = ProcessingFee::create([
                'date' => now(),
                'student_id' => $student->id,
                'amount' => $amount,
                'description' => $data['description'] ?? '',
            ]);

            $this->syncStudentAccount($fee, $amount, $student, false);

            return $fee;
        });
    }

    public function updateProcessingFee(int $id, array $data): ProcessingFee
    {
        return DB::transaction(function () use ($id, $data) {
            $fee = $this->find($id);
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];

            $fee->update([
                'date' => now(),
                'student_id' => $student->id,
                'amount' => $amount,
                'description' => $data['description'] ?? $fee->description,
            ]);

            $this->syncStudentAccount($fee, $amount, $student, true);

            return $fee;
        });
    }

    public function deleteProcessingFee(int $id): void
    {
        ProcessingFee::findOrFail($id)->delete();
    }

    private function loadStudent(int $studentId): Student
    {
        return Student::findOrFail($studentId);
    }

    private function syncStudentAccount(ProcessingFee $fee, float $amount, Student $student, bool $update = false): void
    {
        $payload = [
            'date' => now(),
            'type' => 'processing_fee',
            'student_id' => $student->id,
            'grade_id' => $student->grade_id,
            'classroom_id' => $student->classroom_id,
            'processing_id' => $fee->id,
            'debit' => 0.00,
            'credit' => $amount,
            'description' => $fee->description,
        ];

        if ($update) {
            StudentAccount::where('processing_id', $fee->id)->firstOrFail()->update($payload);
        } else {
            StudentAccount::create($payload);
        }
    }
}
