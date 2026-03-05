<?php

use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function createExamContext(): array
{
    $gender = Gender::create(['name' => ['en' => 'Male', 'ar' => 'ذكر']]);
    $specialization = Specialization::create(['name' => ['en' => 'Math', 'ar' => 'رياضيات']]);

    $teacher = Teacher::create([
        'email' => 'exam-teacher@example.com',
        'password' => Hash::make('password'),
        'name' => ['en' => 'Exam Teacher', 'ar' => 'معلم الاختبارات'],
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => now()->toDateString(),
        'address' => 'School Address',
    ]);

    $grade = Grade::create(['Name' => ['en' => 'Grade A', 'ar' => 'الصف أ']]);

    $classroom = Classroom::create([
        'name' => ['en' => 'Class A', 'ar' => 'فصل أ'],
        'grade_id' => $grade->id,
    ]);

    $section = Section::create([
        'name' => ['en' => 'Section A', 'ar' => 'شعبة أ'],
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    return compact('teacher', 'grade', 'classroom', 'section');
}

test('subjects quizzes and questions pages are accessible', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('subjects.index'))->assertOk();
    $this->actingAs($user)->get(route('quizzes.index'))->assertOk();
    $this->actingAs($user)->get(route('questions.index'))->assertOk();
});

test('can create subject quiz and question with linked relations', function () {
    $user = User::factory()->create();
    $context = createExamContext();

    $this->actingAs($user)->post(route('subjects.store'), [
        'name_ar' => 'مادة الرياضيات',
        'name_en' => 'Mathematics',
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'teacher_id' => $context['teacher']->id,
    ])->assertRedirect(route('subjects.index'));

    $subject = Subject::query()->firstOrFail();

    $this->actingAs($user)->post(route('quizzes.store'), [
        'name_ar' => 'اختبار أول',
        'name_en' => 'First Quiz',
        'subject_id' => $subject->id,
        'teacher_id' => $context['teacher']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
    ])->assertRedirect(route('quizzes.index'));

    $quiz = Quiz::query()->firstOrFail();

    $this->actingAs($user)->post(route('questions.store'), [
        'title' => '2 + 2 = ?',
        'answers' => "3\n4\n5",
        'right_answer' => '4',
        'score' => 5,
        'quiz_id' => $quiz->id,
    ])->assertRedirect(route('questions.index'));

    expect(Subject::count())->toBe(1)
        ->and(Quiz::count())->toBe(1)
        ->and(Question::count())->toBe(1);

    $this->assertDatabaseHas('questions', [
        'quiz_id' => $quiz->id,
        'title' => '2 + 2 = ?',
        'right_answer' => '4',
    ]);
});
