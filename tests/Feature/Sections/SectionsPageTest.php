<?php

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use App\Models\User;

test('sections page renders successfully for authenticated users', function () {
    $user = User::factory()->create();

    $grade = Grade::query()->create([
        'Name' => [
            'ar' => 'المرحلة الأولى',
            'en' => 'First Stage',
        ],
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => [
            'ar' => 'الصف الأول',
            'en' => 'First Classroom',
        ],
        'grade_id' => $grade->id,
    ]);

    Section::query()->create([
        'name' => [
            'ar' => 'قسم أ',
            'en' => 'Section A',
        ],
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'status' => 1,
    ]);

    $response = $this->actingAs($user)->get(route('sections.index'));

    $response->assertOk();
    $response->assertSee('Section A');
});

test('cannot store section when arabic or english name already exists in same grade and classroom', function () {
    $user = User::factory()->create();

    $grade = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الأولى', 'en' => 'First Stage'],
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => ['ar' => 'الصف الأول', 'en' => 'First Classroom'],
        'grade_id' => $grade->id,
    ]);

    Section::query()->create([
        'name' => ['ar' => 'قسم مكرر', 'en' => 'Duplicated Section'],
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'status' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('sections.store'), [
        'name_ar' => 'قسم مكرر',
        'name_en' => 'Duplicated Section',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $response->assertSessionHasErrors(['name_ar', 'name_en']);
});

test('can store same section names in different classroom or grade', function () {
    $user = User::factory()->create();

    $gradeOne = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الأولى', 'en' => 'First Stage'],
        'Notes' => 'Grade one',
    ]);

    $gradeTwo = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الثانية', 'en' => 'Second Stage'],
        'Notes' => 'Grade two',
    ]);

    $classroomOne = Classroom::query()->create([
        'name' => ['ar' => 'الصف الأول', 'en' => 'First Classroom'],
        'grade_id' => $gradeOne->id,
    ]);

    $classroomTwo = Classroom::query()->create([
        'name' => ['ar' => 'الصف الثاني', 'en' => 'Second Classroom'],
        'grade_id' => $gradeOne->id,
    ]);

    $classroomThree = Classroom::query()->create([
        'name' => ['ar' => 'الصف الثالث', 'en' => 'Third Classroom'],
        'grade_id' => $gradeTwo->id,
    ]);

    Section::query()->create([
        'name' => ['ar' => 'قسم مكرر', 'en' => 'Duplicated Section'],
        'grade_id' => $gradeOne->id,
        'classroom_id' => $classroomOne->id,
        'status' => 1,
    ]);

    $differentClassroomResponse = $this->actingAs($user)->post(route('sections.store'), [
        'name_ar' => 'قسم مكرر',
        'name_en' => 'Duplicated Section',
        'grade_id' => $gradeOne->id,
        'classroom_id' => $classroomTwo->id,
    ]);

    $differentClassroomResponse->assertRedirect(route('sections.index'));
    $differentClassroomResponse->assertSessionHasNoErrors();

    $differentGradeResponse = $this->actingAs($user)->post(route('sections.store'), [
        'name_ar' => 'قسم مكرر',
        'name_en' => 'Duplicated Section',
        'grade_id' => $gradeTwo->id,
        'classroom_id' => $classroomThree->id,
    ]);

    $differentGradeResponse->assertRedirect(route('sections.index'));
    $differentGradeResponse->assertSessionHasNoErrors();
});

test('cannot store section when classroom does not belong to selected grade', function () {
    $user = User::factory()->create();

    $gradeOne = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الأولى', 'en' => 'First Stage'],
        'Notes' => 'Grade one',
    ]);

    $gradeTwo = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الثانية', 'en' => 'Second Stage'],
        'Notes' => 'Grade two',
    ]);

    $classroomBelongsToGradeTwo = Classroom::query()->create([
        'name' => ['ar' => 'فصل المرحلة الثانية', 'en' => 'Grade Two Classroom'],
        'grade_id' => $gradeTwo->id,
    ]);

    $response = $this->actingAs($user)->post(route('sections.store'), [
        'name_ar' => 'قسم غير متوافق',
        'name_en' => 'Mismatched Section',
        'grade_id' => $gradeOne->id,
        'classroom_id' => $classroomBelongsToGradeTwo->id,
    ]);

    $response->assertSessionHasErrors(['classroom_id']);
});

test('update ignores current section for unique rule and sets status as boolean', function () {
    $user = User::factory()->create();

    $grade = Grade::query()->create([
        'Name' => ['ar' => 'المرحلة الأولى', 'en' => 'First Stage'],
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => ['ar' => 'الصف الأول', 'en' => 'First Classroom'],
        'grade_id' => $grade->id,
    ]);

    $section = Section::query()->create([
        'name' => ['ar' => 'قسم ثابت', 'en' => 'Stable Section'],
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'status' => 1,
    ]);

    $response = $this->actingAs($user)->patch(route('sections.update', $section), [
        'name_ar' => 'قسم ثابت',
        'name_en' => 'Stable Section',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $response->assertRedirect(route('sections.index'));
    $section->refresh();

    expect((int) $section->status)->toBe(0);
});
