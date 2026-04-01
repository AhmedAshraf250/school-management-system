<?php

use App\Models\Attendance;
use App\Models\BloodType;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\FeeInvoice;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Payment;
use App\Models\Religion;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

test('guest is redirected when opening student calendar page', function () {
    $this->get(route('student.calendar'))
        ->assertRedirect(route('auth.selection'));
});

test('guest is redirected when opening guardian calendar page', function () {
    $this->get(route('guardian.calendar'))
        ->assertRedirect(route('auth.selection'));
});

test('guardian can open dashboard tabs pages', function () {
    $context = createGuardianDashboardContext();
    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard'))->assertSuccessful();
    $this->get(route('guardian.dashboard.attendance'))->assertSuccessful();
    $this->get(route('guardian.dashboard.financial'))->assertSuccessful();
    $this->get(route('guardian.profile'))->assertSuccessful();
});

test('guardian attendance tab shows selected child daily status', function () {
    $context = createGuardianDashboardContext();

    Attendance::query()->create([
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher_id'],
        'attendence_date' => '2026-03-20',
        'attendence_status' => true,
    ]);

    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard.attendance', [
        'student_id' => $context['student']->id,
        'date' => '2026-03-20',
    ]))
        ->assertSuccessful()
        ->assertSeeText('Child One')
        ->assertSeeText('Present');
});

test('guardian dashboard aggregates daily attendance per child across teachers', function () {
    $context = createGuardianDashboardContext();
    $primaryTeacher = Teacher::query()->findOrFail($context['teacher_id']);

    $secondTeacher = Teacher::query()->create([
        'email' => 'guardian-second-teacher@example.com',
        'password' => Hash::make('password'),
        'name' => 'Teacher Two',
        'specialization_id' => $primaryTeacher->specialization_id,
        'gender_id' => $primaryTeacher->gender_id,
        'joining_date' => '2026-01-02',
        'address' => 'Cairo',
    ]);

    $secondStudent = Student::query()->create([
        'name' => 'Child Two',
        'email' => 'guardian-second-child@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $context['student']->gender_id,
        'nationality_id' => $context['student']->nationality_id,
        'blood_id' => $context['student']->blood_id,
        'date_birth' => '2013-01-02',
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'guardian_id' => $context['guardian']->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    $today = now()->toDateString();

    Attendance::query()->create([
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher_id'],
        'attendence_date' => $today,
        'attendence_status' => false,
    ]);

    Attendance::query()->create([
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $secondTeacher->id,
        'attendence_date' => $today,
        'attendence_status' => true,
    ]);

    Attendance::query()->create([
        'student_id' => $secondStudent->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher_id'],
        'attendence_date' => $today,
        'attendence_status' => false,
    ]);

    Attendance::query()->create([
        'student_id' => $secondStudent->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $secondTeacher->id,
        'attendence_date' => $today,
        'attendence_status' => false,
    ]);

    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard'))
        ->assertSuccessful()
        ->assertViewHas('presentTodayCount', 1)
        ->assertViewHas('absentTodayCount', 1)
        ->assertViewHas('unrecordedTodayCount', 0);
});

test('guardian dashboard excludes graduated students from active children overview', function () {
    $context = createGuardianDashboardContext();

    $graduatedStudent = Student::query()->create([
        'name' => 'Graduated Child',
        'email' => 'graduated-child@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $context['student']->gender_id,
        'nationality_id' => $context['student']->nationality_id,
        'blood_id' => $context['student']->blood_id,
        'date_birth' => '2013-01-03',
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'guardian_id' => $context['guardian']->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_GRADUATED,
    ]);

    Attendance::query()->create([
        'student_id' => $graduatedStudent->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'section_id' => $context['section']->id,
        'teacher_id' => $context['teacher_id'],
        'attendence_date' => now()->toDateString(),
        'attendence_status' => true,
    ]);

    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard'))
        ->assertSuccessful()
        ->assertSeeText('Child One')
        ->assertDontSeeText('Graduated Child')
        ->assertViewHas('students', fn ($students) => $students->count() === 1);
});

test('guardian financial tab shows invoices and account movements for selected child', function () {
    $context = createGuardianDashboardContext();

    $fee = Fee::query()->create([
        'title' => 'Tuition Fees',
        'amount' => 500,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'description' => 'Term one',
        'year' => '2025',
        'type' => 1,
    ]);

    FeeInvoice::query()->create([
        'invoice_date' => '2026-03-01',
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'fee_id' => $fee->id,
        'amount' => 500,
        'description' => 'Invoice for tuition',
    ]);

    StudentAccount::query()->create([
        'date' => '2026-03-01',
        'type' => 'fee_invoice',
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'debit' => 500,
        'credit' => 0,
        'description' => 'Invoice entry',
    ]);

    StudentAccount::query()->create([
        'date' => '2026-03-10',
        'type' => 'payment',
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'payment_id' => Payment::query()->create([
            'date' => '2026-03-10',
            'student_id' => $context['student']->id,
            'amount' => 200,
            'description' => 'Payment entry',
        ])->id,
        'debit' => 0,
        'credit' => 200,
        'description' => 'Payment entry',
    ]);

    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard.financial', [
        'student_id' => $context['student']->id,
    ]))
        ->assertSuccessful()
        ->assertSeeText('Tuition Fees')
        ->assertSeeText('Invoice for tuition')
        ->assertSeeText('Invoice entry')
        ->assertSeeText('Payment entry')
        ->assertSeeText('500.00')
        ->assertSeeText('200.00')
        ->assertSeeText('300.00');
});

test('guardian financial report ignores soft deleted financial operations', function () {
    $context = createGuardianDashboardContext();

    $payment = Payment::query()->create([
        'date' => now()->toDateString(),
        'student_id' => $context['student']->id,
        'amount' => 150,
        'description' => 'Soft deleted payment',
    ]);

    StudentAccount::query()->create([
        'date' => now()->toDateString(),
        'type' => 'payment',
        'student_id' => $context['student']->id,
        'grade_id' => $context['grade']->id,
        'classroom_id' => $context['classroom']->id,
        'payment_id' => $payment->id,
        'debit' => 150,
        'credit' => 0,
        'description' => 'Linked payment',
    ]);

    $payment->delete();

    $this->actingAs($context['guardian'], 'guardian');

    $this->get(route('guardian.dashboard.financial', [
        'student_id' => $context['student']->id,
    ]))
        ->assertSuccessful()
        ->assertViewHas('accountEntries', fn ($entries) => $entries->isEmpty())
        ->assertViewHas('totalDebit', 0.0)
        ->assertViewHas('totalCredit', 0.0)
        ->assertViewHas('outstandingAmount', 0.0);
});

function createGuardianDashboardContext(): array
{
    $gender = Gender::query()->create(['name' => 'Male']);
    $nationality = Nationality::query()->create(['name' => 'Egyptian']);
    $bloodType = BloodType::query()->create(['name' => 'O+']);
    $religion = Religion::query()->create(['name' => 'Islam']);
    $specialization = Specialization::query()->create(['name' => 'Math']);

    $guardian = Guardian::query()->create([
        'email' => 'guardian-dashboard@example.com',
        'password' => Hash::make('password'),
        'father_name' => 'Guardian Father',
        'father_national_id' => '11111111111111',
        'father_passport_id' => 'P111111',
        'father_phone' => '01000000000',
        'father_job' => 'Engineer',
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => $religion->id,
        'father_address' => 'Cairo',
        'mother_name' => 'Guardian Mother',
        'mother_national_id' => '22222222222222',
        'mother_passport_id' => 'P222222',
        'mother_phone' => '01000000001',
        'mother_job' => 'Teacher',
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => $religion->id,
        'mother_address' => 'Cairo',
    ]);

    $grade = Grade::query()->create([
        'Name' => 'Grade 1',
        'Notes' => 'Test grade',
    ]);

    $classroom = Classroom::query()->create([
        'name' => 'Class A',
        'grade_id' => $grade->id,
    ]);

    $section = Section::query()->create([
        'name' => 'Section 1',
        'status' => true,
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
    ]);

    $student = Student::query()->create([
        'name' => 'Child One',
        'email' => 'guardian-child@example.com',
        'password' => Hash::make('password'),
        'gender_id' => $gender->id,
        'nationality_id' => $nationality->id,
        'blood_id' => $bloodType->id,
        'date_birth' => '2013-01-01',
        'grade_id' => $grade->id,
        'classroom_id' => $classroom->id,
        'section_id' => $section->id,
        'guardian_id' => $guardian->id,
        'academic_year' => '2025-2026',
        'status' => Student::STATUS_ACTIVE,
    ]);

    $teacher = Teacher::query()->create([
        'email' => 'guardian-teacher@example.com',
        'password' => Hash::make('password'),
        'name' => 'Teacher One',
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => '2026-01-01',
        'address' => 'Cairo',
    ]);

    return [
        'guardian' => $guardian,
        'student' => $student,
        'grade' => $grade,
        'classroom' => $classroom,
        'section' => $section,
        'teacher_id' => $teacher->id,
    ];
}
