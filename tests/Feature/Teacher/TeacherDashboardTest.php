<?php

use App\Models\BloodType;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Database\Seeders\SubjectSeeder;
use Illuminate\Support\Facades\Hash;

test('teacher dashboard shows teacher specific metrics', function () {
    [$teacher] = seedTeacherWithSectionsAndStudents();

    $this->actingAs($teacher, 'teacher');

    $this->get(route('teacher.dashboard'))
        ->assertSuccessful()
        ->assertSeeText('Welcome')
        ->assertSeeText((string) 2)
        ->assertSeeText((string) 3);
});

test('teacher students index shows only students inside teacher sections', function () {
    [$teacher, $otherSectionStudent] = seedTeacherWithSectionsAndStudents();

    $this->actingAs($teacher, 'teacher');

    $this->get(route('teacher.students.index'))
        ->assertSuccessful()
        ->assertSeeText('Student One')
        ->assertSeeText('Student Two')
        ->assertSeeText('Student Three')
        ->assertDontSeeText($otherSectionStudent->name);
});

test('teacher cannot open student profile outside assigned sections', function () {
    [$teacher, $otherSectionStudent] = seedTeacherWithSectionsAndStudents();

    $this->actingAs($teacher, 'teacher');

    $this->get(route('teacher.students.show', $otherSectionStudent->id))
        ->assertForbidden();
});

test('teacher can open dedicated calendar page', function () {
    [$teacher] = seedTeacherWithSectionsAndStudents();

    $this->actingAs($teacher, 'teacher');

    $this->get(route('teacher.calendar'))
        ->assertSuccessful();
});

test('subject seeder gives each teacher with sections at least one subject', function () {
    $grade = Grade::query()->create([
        'Name' => 'Grade 1',
        'Notes' => 'Seeder grade',
    ]);

    $classroomOne = Classroom::query()->create([
        'name' => 'Class A',
        'grade_id' => $grade->id,
    ]);

    $classroomTwo = Classroom::query()->create([
        'name' => 'Class B',
        'grade_id' => $grade->id,
    ]);

    $sectionOne = Section::query()->create([
        'name' => 'Section 1',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroomOne->id,
    ]);

    $sectionTwo = Section::query()->create([
        'name' => 'Section 2',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroomTwo->id,
    ]);

    $specialization = Specialization::query()->create(['name' => 'Science']);
    $gender = Gender::query()->create(['name' => 'Male']);

    $teacherOne = Teacher::query()->create([
        'email' => 'teacher-one@school.test',
        'password' => Hash::make('password'),
        'name' => 'Teacher One',
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => '2026-01-01',
        'address' => 'Cairo',
    ]);

    $teacherTwo = Teacher::query()->create([
        'email' => 'teacher-two@school.test',
        'password' => Hash::make('password'),
        'name' => 'Teacher Two',
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => '2026-01-01',
        'address' => 'Cairo',
    ]);

    $teacherOne->sections()->attach([$sectionOne->id]);
    $teacherTwo->sections()->attach([$sectionTwo->id]);

    $this->seed(SubjectSeeder::class);

    $teacherIds = [$teacherOne->id, $teacherTwo->id];

    foreach ($teacherIds as $teacherId) {
        expect(
            Subject::query()->where('teacher_id', $teacherId)->exists()
        )->toBeTrue();
    }
});

function seedTeacherWithSectionsAndStudents(): array
{
    $grade = Grade::query()->create([
        'Name' => 'Grade 1',
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => 'Class A',
        'grade_id' => $grade->id,
    ]);

    $gender = Gender::query()->create(['name' => 'Male']);
    $nationality = Nationality::query()->create(['name' => 'Egyptian']);
    $bloodType = BloodType::query()->create(['name' => 'O+']);
    $religion = Religion::query()->create(['name' => 'Islam']);
    $specialization = Specialization::query()->create(['name' => 'Math']);

    $guardian = Guardian::query()->create([
        'email' => 'guardian@example.com',
        'password' => Hash::make('password'),
        'father_name' => 'Father Name',
        'father_national_id' => '11111111111111',
        'father_passport_id' => 'P111111',
        'father_phone' => '01000000000',
        'father_job' => 'Engineer',
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => $religion->id,
        'father_address' => 'Cairo',
        'mother_name' => 'Mother Name',
        'mother_national_id' => '22222222222222',
        'mother_passport_id' => 'P222222',
        'mother_phone' => '01000000001',
        'mother_job' => 'Teacher',
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => $religion->id,
        'mother_address' => 'Cairo',
    ]);

    $teacher = Teacher::query()->create([
        'email' => 'teacher@example.com',
        'password' => Hash::make('password'),
        'name' => 'Teacher One',
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => '2026-01-01',
        'address' => 'Cairo',
    ]);

    $firstSection = Section::query()->create([
        'name' => 'Section 1',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $secondSection = Section::query()->create([
        'name' => 'Section 2',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $thirdSection = Section::query()->create([
        'name' => 'Section 3',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $teacher->sections()->attach([$firstSection->id, $secondSection->id]);

    Student::query()->create([
        'name' => 'Student One',
        'email' => 'student1@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2012-01-01',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $firstSection->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    Student::query()->create([
        'name' => 'Student Two',
        'email' => 'student2@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2012-01-02',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $firstSection->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    Student::query()->create([
        'name' => 'Student Three',
        'email' => 'student3@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2012-01-03',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $secondSection->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    $otherSectionStudent = Student::query()->create([
        'name' => 'Outside Student',
        'email' => 'outside@student.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2012-01-04',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $thirdSection->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    return [$teacher, $otherSectionStudent];
}
