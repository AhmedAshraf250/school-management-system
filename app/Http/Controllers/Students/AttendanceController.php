<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepoistoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceRepoistoryInterface $attendanceRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $grades = $this->attendanceRepository->allSections();

        return view('pages.attendances.sections', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        try {
            $this->attendanceRepository->storeAttendance($request->validated());
            toastr()->success(trans('messages.success'));

            return redirect()->route('attendances.show', $request->integer('section_id'));
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $sectionId): View
    {
        $section = $this->attendanceRepository->sectionWithStudents($sectionId);
        $students = $section->students;
        $teacherId = $section->teachers->first()?->id;

        return view('pages.attendances.index', compact('section', 'students', 'teacherId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance): void
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance): void
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance): void
    {
        abort(404);
    }
}
