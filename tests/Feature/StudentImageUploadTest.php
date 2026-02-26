<?php

use App\Models\BloodType;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Image;
use App\Models\Nationality;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('student images are uploaded and saved when creating a student', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $grade = Grade::query()->create([
        'Name' => ['en' => 'Grade 1', 'ar' => 'الصف الاول'],
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => ['en' => 'Class A', 'ar' => 'الفصل أ'],
        'grade_id' => $grade->id,
    ]);

    $section = Section::query()->create([
        'name' => ['en' => 'Section 1', 'ar' => 'الشعبة 1'],
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'status' => true,
    ]);

    $gender = Gender::query()->create([
        'name' => ['en' => 'Male', 'ar' => 'ذكر'],
    ]);

    $nationality = Nationality::query()->create([
        'name' => ['en' => 'Egyptian', 'ar' => 'مصري'],
    ]);

    $bloodType = BloodType::query()->create([
        'name' => 'A+',
    ]);

    $guardian = Guardian::query()->create([
        'email' => 'guardian@example.com',
        'password' => Hash::make('password123'),
        'father_name' => ['en' => 'Father Name', 'ar' => 'اسم الاب'],
        'father_national_id' => '11111111111111',
        'father_passport_id' => 'P123456',
        'father_phone' => '01000000000',
        'father_job' => ['en' => 'Engineer', 'ar' => 'مهندس'],
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => null,
        'father_address' => 'Cairo',
        'mother_name' => ['en' => 'Mother Name', 'ar' => 'اسم الام'],
        'mother_national_id' => '22222222222222',
        'mother_passport_id' => 'P654321',
        'mother_phone' => '01000000001',
        'mother_job' => ['en' => 'Teacher', 'ar' => 'معلمة'],
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => null,
        'mother_address' => 'Cairo',
    ]);

    $photoOne = UploadedFile::fake()->image('student-one.jpg');
    $photoTwo = UploadedFile::fake()->image('student-two.jpg');

    $response = $this->actingAs($user)->post(route('students.store'), [
        'name_ar' => 'طالب تجريبي',
        'name_en' => 'Test Student',
        'email' => 'student@example.com',
        'password' => 'password123',
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2010-01-01',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $section->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025',
        'photos' => [$photoOne, $photoTwo],
    ]);

    $response->assertRedirect(route('students.index'));

    $student = \App\Models\Student::query()->firstOrFail();

    expect($student->images()->count())->toBe(2);

    $student->images()->each(function (\App\Models\Image $image): void {
        Storage::disk('public')->assertExists($image->path);
    });

    $this->assertDatabaseCount('images', 2);
});

test('upload attachments redirects back to student details page', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $student = createStudentRecord('upload');

    $response = $this->actingAs($user)->post(route('students.uploadAttachments', $student->id), [
        'photos' => [UploadedFile::fake()->image('extra-photo.jpg')],
    ]);

    $response->assertRedirect(route('students.show', $student->id));
    expect($student->fresh()->images()->count())->toBe(1);
});

test('attachment download enforces student ownership and delete removes physical file', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $studentOne = createStudentRecord('owner');
    $studentTwo = createStudentRecord('other');

    $this->actingAs($user)->post(route('students.uploadAttachments', $studentOne->id), [
        'photos' => [UploadedFile::fake()->image('owned-photo.jpg')],
    ]);

    /** @var Image $attachment */
    $attachment = $studentOne->fresh()->images()->firstOrFail();

    $downloadResponse = $this->actingAs($user)->get(route('students.downloadAttachment', [
        'student' => $studentTwo->id,
        'attachmentId' => $attachment->id,
    ]));
    $downloadResponse->assertNotFound();

    Storage::disk('public')->assertExists($attachment->path);

    $deleteResponse = $this->actingAs($user)->delete(route('students.deleteAttachment', [
        'student' => $studentOne->id,
        'attachmentId' => $attachment->id,
    ]));

    $deleteResponse->assertRedirect(route('students.show', $studentOne->id));

    Storage::disk('public')->assertMissing($attachment->path);
    $this->assertDatabaseMissing('images', ['id' => $attachment->id]);
});

function createStudentRecord(string $suffix): Student
{
    $grade = Grade::query()->create([
        'Name' => ['en' => 'Grade '.$suffix, 'ar' => 'الصف '.$suffix],
        'Notes' => 'Test grade '.$suffix,
    ]);

    $classroom = Classroom::query()->create([
        'name' => ['en' => 'Class '.$suffix, 'ar' => 'فصل '.$suffix],
        'grade_id' => $grade->id,
    ]);

    $section = Section::query()->create([
        'name' => ['en' => 'Section '.$suffix, 'ar' => 'شعبة '.$suffix],
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'status' => true,
    ]);

    $gender = Gender::query()->create([
        'name' => ['en' => 'Male '.$suffix, 'ar' => 'ذكر '.$suffix],
    ]);

    $nationality = Nationality::query()->create([
        'name' => ['en' => 'Egyptian '.$suffix, 'ar' => 'مصري '.$suffix],
    ]);

    $bloodType = BloodType::query()->create([
        'name' => 'A+ '.$suffix,
    ]);

    $guardian = Guardian::query()->create([
        'email' => 'guardian_'.$suffix.'@example.com',
        'password' => Hash::make('password123'),
        'father_name' => ['en' => 'Father '.$suffix, 'ar' => 'اب '.$suffix],
        'father_national_id' => '1'.str_pad((string) random_int(1, 9999999999999), 13, '0', STR_PAD_LEFT),
        'father_passport_id' => 'P'.$suffix.'F',
        'father_phone' => '010'.str_pad((string) random_int(1, 99999999), 8, '0', STR_PAD_LEFT),
        'father_job' => ['en' => 'Engineer '.$suffix, 'ar' => 'مهندس '.$suffix],
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => null,
        'father_address' => 'Cairo',
        'mother_name' => ['en' => 'Mother '.$suffix, 'ar' => 'ام '.$suffix],
        'mother_national_id' => '2'.str_pad((string) random_int(1, 9999999999999), 13, '0', STR_PAD_LEFT),
        'mother_passport_id' => 'P'.$suffix.'M',
        'mother_phone' => '011'.str_pad((string) random_int(1, 99999999), 8, '0', STR_PAD_LEFT),
        'mother_job' => ['en' => 'Teacher '.$suffix, 'ar' => 'معلمة '.$suffix],
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => null,
        'mother_address' => 'Cairo',
    ]);

    return Student::query()->create([
        'name' => ['en' => 'Student '.$suffix, 'ar' => 'طالب '.$suffix],
        'email' => 'student_'.$suffix.'@example.com',
        'password' => Hash::make('password123'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2010-01-01',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $section->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025',
    ]);
}
