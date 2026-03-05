<?php

use App\Models\BloodType;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function createAttendanceContext(): array
{
    $gender = Gender::create(['name' => ['en' => 'Male', 'ar' => 'ذكر']]);
    $nationality = Nationality::create(['name' => ['en' => 'Egyptian', 'ar' => 'مصري']]);
    $bloodType = BloodType::create(['name' => 'O+']);
    $religion = Religion::create(['name' => ['en' => 'Muslim', 'ar' => 'مسلم']]);

    $guardian = Guardian::create([
        'email' => 'guardian@example.com',
        'password' => Hash::make('password'),
        'father_name' => ['en' => 'Father', 'ar' => 'الأب'],
        'father_national_id' => '1234567890',
        'father_passport_id' => 'P123456',
        'father_phone' => '01000000000',
        'father_job' => ['en' => 'Engineer', 'ar' => 'مهندس'],
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => $religion->id,
        'father_address' => 'Address 1',
        'mother_name' => ['en' => 'Mother', 'ar' => 'الأم'],
        'mother_national_id' => '1098765432',
        'mother_passport_id' => 'P654321',
        'mother_phone' => '01111111111',
        'mother_job' => ['en' => 'Teacher', 'ar' => 'معلمة'],
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => $religion->id,
        'mother_address' => 'Address 2',
    ]);

    $grade = Grade::create(['Name' => ['en' => 'Grade 1', 'ar' => 'الصف الأول']]);
    $classroom = $grade->classrooms()->create(['name' => ['en' => 'Class A', 'ar' => 'فصل أ']]);
    $section = Section::create([
        'name' => ['en' => 'Section 1', 'ar' => 'شعبة 1'],
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $specialization = Specialization::create(['name' => ['en' => 'Math', 'ar' => 'رياضيات']]);
    $teacher = Teacher::create([
        'email' => 'teacher@example.com',
        'password' => Hash::make('password'),
        'name' => ['en' => 'Teacher One', 'ar' => 'المعلم الأول'],
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => now()->toDateString(),
        'address' => 'School street',
    ]);
    $section->teachers()->attach($teacher->id);

    $student = Student::create([
        'name' => ['en' => 'Student One', 'ar' => 'الطالب الأول'],
        'email' => 'student@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2015-01-01',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $section->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    return compact('section', 'teacher', 'student');
}

test('attendance page shows direct link to student show page', function () {
    $user = User::factory()->create();
    $context = createAttendanceContext();

    $response = $this->actingAs($user)->get(route('attendances.show', $context['section']->id));

    $response->assertOk();
    $response->assertSee(route('students.show', $context['student']->id), false);
});

test('attendance store saves today attendance for selected student', function () {
    $user = User::factory()->create();
    $context = createAttendanceContext();

    $response = $this->actingAs($user)->post(route('attendances.store'), [
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher']->id,
        'attendence_date' => now()->toDateString(),
        'student_ids' => [$context['student']->id],
        'attendences' => [
            $context['student']->id => 'presence',
        ],
    ]);

    $response->assertRedirect(route('attendances.show', $context['section']->id));

    $this->assertDatabaseHas('attendances', [
        'student_id' => $context['student']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher']->id,
        'attendence_date' => now()->startOfDay()->toDateTimeString(),
        'attendence_status' => 1,
    ]);
});
