<?php

namespace App\Http\Controllers\Guardians;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guardians\UpdateGuardianPasswordRequest;
use App\Models\Attendance;
use App\Models\FeeInvoice;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\StudentAccount;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $guardian = $this->authenticatedGuardian();
        $students = $this->guardianStudents($guardian);
        $studentIds = $students->pluck('id')->all();
        $todayRecords = Attendance::query()
            ->whereIn('student_id', $studentIds)
            ->whereDate('attendence_date', now()->toDateString())
            ->get(['id', 'student_id', 'attendence_status']);

        $todayRecordsByStudent = $todayRecords->groupBy('student_id'); // الحضور والغياب بالطالب اليوم (تم_تسجيلهم)
        $recordedTodayCount = $todayRecordsByStudent->count(); // (عددهم) الحضور والغياب المسجل اليوم

        $presentTodayCount = $todayRecordsByStudent
            ->filter(fn (Collection $records) => $records->contains(
                fn (Attendance $attendance) => $attendance->attendence_status
            ))
            ->count(); // عدد الحضور المسجل اليوم

        $absentTodayCount = $recordedTodayCount - $presentTodayCount;
        $unrecordedTodayCount = max($students->count() - $recordedTodayCount, 0);
        $outstandingAmount = StudentAccount::query()
            ->includedInTotals()
            ->whereIn('student_id', $studentIds)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->value('balance');

        return view('pages.guardians.dashboard.dashboard', [
            'guardian' => $guardian,
            'students' => $students,
            'presentTodayCount' => $presentTodayCount,
            'absentTodayCount' => $absentTodayCount,
            'unrecordedTodayCount' => $unrecordedTodayCount,
            'outstandingAmount' => $outstandingAmount,
        ]);
    }

    public function attendance(Request $request)
    {
        $guardian = $this->authenticatedGuardian();
        $students = $this->guardianStudents($guardian);
        $selectedStudent = $this->selectedStudent($students, $request->integer('student_id'));
        $selectedDate = $this->selectedDate($request->string('date')->toString());

        $attendanceRecord = null;
        if ($selectedStudent instanceof Student) {
            $attendanceRecord = Attendance::query()
                ->where('student_id', $selectedStudent->id)
                ->whereDate('attendence_date', $selectedDate)
                ->get(['id', 'student_id', 'attendence_status']);
        }

        $attendanceStatus = $this->dailyStatus($attendanceRecord);

        return view('pages.guardians.dashboard.attendance', [
            'guardian' => $guardian,
            'students' => $students,
            'selectedStudent' => $selectedStudent,
            'selectedDate' => $selectedDate,
            'attendanceStatus' => $attendanceStatus,
        ]);
    }

    public function financialReports(Request $request)
    {
        $guardian = $this->authenticatedGuardian();
        $students = $this->guardianStudents($guardian);
        $selectedStudent = $this->selectedStudent($students, $request->integer('student_id'));

        $invoices = collect();
        $accountEntries = collect();
        $totalInvoicesAmount = 0.0;
        $totalDebit = 0.0;
        $totalCredit = 0.0;
        $outstandingAmount = 0.0;

        if ($selectedStudent instanceof Student) {
            $invoices = FeeInvoice::query()
                ->where('student_id', $selectedStudent->id)
                ->with('fee:id,title')
                ->latest('invoice_date')
                ->get();

            $accountEntries = StudentAccount::query()
                ->includedInTotals()
                ->where('student_id', $selectedStudent->id)
                ->latest('date')
                ->get();

            $totalInvoicesAmount = (float) $invoices->sum('amount');
            $totalDebit = (float) $accountEntries->sum('debit');
            $totalCredit = (float) $accountEntries->sum('credit');
            $outstandingAmount = $totalDebit - $totalCredit;
        }

        return view('pages.guardians.dashboard.financial-reports', [
            'guardian' => $guardian,
            'students' => $students,
            'selectedStudent' => $selectedStudent,
            'invoices' => $invoices,
            'accountEntries' => $accountEntries,
            'totalInvoicesAmount' => $totalInvoicesAmount,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'outstandingAmount' => $outstandingAmount,
        ]);
    }

    public function profile()
    {
        $guardian = $this->authenticatedGuardian();
        $guardian->load([
            'fatherNational:id,name',
            'motherNational:id,name',
            'fatherBloodType:id,name',
            'motherBloodType:id,name',
            'fatherReligion:id,name',
            'motherReligion:id,name',
        ]);
        $studentsCount = Student::query()
            ->where('guardian_id', $guardian->id)
            ->where('status', Student::STATUS_ACTIVE)
            ->count();

        return view('pages.guardians.dashboard.profile', [
            'guardian' => $guardian,
            'studentsCount' => $studentsCount,
        ]);
    }

    public function updatePassword(UpdateGuardianPasswordRequest $request): RedirectResponse
    {
        $guardian = $this->authenticatedGuardian();
        $guardian->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        toastr()->success(trans('messages.success'));

        return redirect()->route('guardian.profile')->with('status', 'password-updated');
    }

    public function calendar()
    {
        $guardian = $this->authenticatedGuardian();

        return view('pages.guardians.dashboard.calendar', [
            'guardian' => $guardian,
        ]);
    }

    private function authenticatedGuardian(): Guardian
    {
        $authenticatedGuardian = Auth::guard('guardian')->user();

        if (! $authenticatedGuardian instanceof Guardian) {
            abort(403);
        }

        return $authenticatedGuardian;
    }

    private function guardianStudents(Guardian $guardian)
    {
        return Student::query()
            ->where('guardian_id', $guardian->id)
            ->where('status', Student::STATUS_ACTIVE)
            ->with([
                'grade:id,Name',
                'classroom:id,name',
                'section:id,name',
                'images:id,imageable_id,imageable_type,path,file_name',
            ])
            ->orderBy('id')
            ->get();
    }

    private function selectedStudent(Collection $students, int $selectedStudentId): ?Student
    {
        $selectedStudent = $students->firstWhere('id', $selectedStudentId);

        if (! $selectedStudent instanceof Student) {
            $selectedStudent = $students->first();
        }

        return $selectedStudent;
    }

    private function selectedDate(?string $date)
    {
        if ($date === null || trim($date) === '') {
            return now()->toDateString();
        }

        try {
            return Carbon::parse($date)->toDateString();
        } catch (\Throwable) {
            return now()->toDateString();
        }
    }

    private function dailyStatus(?Collection $records)
    {
        if (! $records instanceof Collection || $records->isEmpty()) {
            return 'unrecorded';
        }

        $hasPresence = $records->contains(
            fn (Attendance $attendance) => (bool) $attendance->attendence_status
        );

        if ($hasPresence) {
            return 'present';
        }

        return 'absent';
    }
}
