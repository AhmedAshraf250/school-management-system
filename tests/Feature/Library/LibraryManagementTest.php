<?php

use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Library;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('can store download and delete library book attachment', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $context = createLibraryContext();

    $storeResponse = $this->actingAs($user)->post(route('libraries.store'), [
        'title' => 'Algebra Basics',
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher']->id,
        'file' => UploadedFile::fake()->create('algebra-basics.pdf', 100, 'application/pdf'),
    ]);

    $storeResponse->assertRedirect(route('libraries.index'));

    /** @var Library $book */
    $book = Library::query()->firstOrFail();

    expect($book->title)->toBe('Algebra Basics')
        ->and($book->path)->not->toBeEmpty();

    Storage::disk('public')->assertExists($book->path);

    $downloadResponse = $this->actingAs($user)->get(route('libraries.download', $book->id));
    $downloadResponse->assertSuccessful();

    $deleteResponse = $this->actingAs($user)->delete(route('libraries.destroy', $book->id));
    $deleteResponse->assertRedirect(route('libraries.index'));

    Storage::disk('public')->assertMissing($book->path);
    $this->assertDatabaseCount('libraries', 0);
});

function createLibraryContext(): array
{
    $gender = Gender::query()->create([
        'name' => ['en' => 'Male', 'ar' => 'ذكر'],
    ]);

    $specialization = Specialization::query()->create([
        'name' => ['en' => 'Mathematics', 'ar' => 'الرياضيات'],
    ]);

    $teacher = Teacher::query()->create([
        'email' => 'library-teacher@example.com',
        'password' => Hash::make('password123'),
        'name' => ['en' => 'Library Teacher', 'ar' => 'معلم المكتبة'],
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => now()->toDateString(),
        'address' => 'School Address',
    ]);

    $grade = Grade::query()->create([
        'Name' => ['en' => 'Grade 1', 'ar' => 'الصف الأول'],
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => ['en' => 'Class A', 'ar' => 'الفصل أ'],
        'grade_id' => $grade->id,
    ]);

    $section = Section::query()->create([
        'name' => ['en' => 'Section 1', 'ar' => 'الشعبة 1'],
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    return compact('teacher', 'grade', 'classroom', 'section');
}
