<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherIndirectOnlineClassRequest;
use App\Http\Requests\Teacher\StoreTeacherOnlineClassRequest;
use App\Models\onlineClass;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Jubaer\Zoom\Facades\Zoom;

class OnlineClassController extends Controller
{
    public function index()
    {
        $teacher = $this->authenticatedTeacher();

        $onlineClasses = onlineClass::query()
            ->with(['grade', 'classroom', 'section', 'teacherCreator'])
            ->where('created_by', $teacher->email)
            ->latest()
            ->get();

        return view('pages.teachers.dashboard.online-classes.index', [
            'teacher' => $teacher,
            'online_classes' => $onlineClasses,
        ]);
    }

    public function create()
    {
        $teacher = $this->authenticatedTeacher();

        return view('pages.teachers.dashboard.online-classes.add', [
            'teacherSections' => $this->teacherSections($teacher),
        ]);
    }

    public function indirectCreate()
    {
        $teacher = $this->authenticatedTeacher();

        return view('pages.teachers.dashboard.online-classes.indirect', [
            'teacherSections' => $this->teacherSections($teacher),
        ]);
    }

    public function store(StoreTeacherOnlineClassRequest $request)
    {
        try {
            $teacher = $this->authenticatedTeacher();
            $validatedData = $request->validated();
            $sectionData = $this->authorizedSectionPayload($teacher, (int) $validatedData['section_id']);
            $password = $validatedData['password'] ?? Str::upper(Str::random(8));

            $meetingResponse = Zoom::createMeeting([
                'topic' => $validatedData['topic'],
                'type' => 2,
                'duration' => $validatedData['duration'],
                'timezone' => config('app.timezone', 'UTC'),
                'password' => $password,
                'start_time' => $validatedData['start_time'],
                'settings' => [
                    'join_before_host' => false,
                    'host_video' => false,
                    'participant_video' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                    'audio' => 'both',
                    'auto_recording' => 'none',
                    'approval_type' => 0,
                ],
            ]);

            if (($meetingResponse['status'] ?? false) !== true) {
                throw new \RuntimeException($meetingResponse['message'] ?? 'Zoom meeting could not be created.');
            }

            $meetingData = $meetingResponse['data'] ?? [];

            onlineClass::query()->create([
                'integration' => true,
                'grade_id' => $sectionData['grade_id'],
                'classroom_id' => $sectionData['classroom_id'],
                'section_id' => $sectionData['section_id'],
                'created_by' => $teacher->email,
                'meeting_id' => (string) ($meetingData['id'] ?? ''),
                'topic' => $validatedData['topic'],
                'start_at' => $this->normalizeDateTime((string) ($meetingData['start_time'] ?? $validatedData['start_time'])),
                'duration' => (int) ($meetingData['duration'] ?? $validatedData['duration']),
                'password' => (string) ($meetingData['password'] ?? $password),
                'start_url' => (string) ($meetingData['start_url'] ?? ''),
                'join_url' => (string) ($meetingData['join_url'] ?? ''),
            ]);

            toastr()->success(trans('messages.success'));

            return redirect()->route('teacher.online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function storeIndirect(StoreTeacherIndirectOnlineClassRequest $request)
    {
        try {
            $teacher = $this->authenticatedTeacher();
            $validatedData = $request->validated();
            $sectionData = $this->authorizedSectionPayload($teacher, (int) $validatedData['section_id']);

            onlineClass::query()->create([
                'integration' => false,
                'grade_id' => $sectionData['grade_id'],
                'classroom_id' => $sectionData['classroom_id'],
                'section_id' => $sectionData['section_id'],
                'created_by' => $teacher->email,
                'meeting_id' => $validatedData['meeting_id'],
                'topic' => $validatedData['topic'],
                'start_at' => $this->normalizeDateTime($validatedData['start_time']),
                'duration' => (int) $validatedData['duration'],
                'password' => $validatedData['password'],
                'start_url' => $validatedData['start_url'],
                'join_url' => $validatedData['join_url'],
            ]);

            toastr()->success(trans('messages.success'));

            return redirect()->route('teacher.online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(onlineClass $onlineClass)
    {
        $teacher = $this->authenticatedTeacher();
        $this->ensureOwnedByTeacher($onlineClass, $teacher);

        try {
            if ((bool) $onlineClass->integration === true) {
                $zoomDeleteResponse = Zoom::deleteMeeting($onlineClass->meeting_id);

                if (($zoomDeleteResponse['status'] ?? false) !== true) {
                    logger()->warning('Zoom meeting deletion failed for teacher class.', [
                        'meeting_id' => $onlineClass->meeting_id,
                        'teacher_email' => $teacher->email,
                        'message' => $zoomDeleteResponse['message'] ?? 'Unknown zoom error',
                    ]);
                }
            }

            $onlineClass->delete();

            toastr()->success(trans('messages.Delete'));

            return redirect()->route('teacher.online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    private function authenticatedTeacher(): Teacher
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }

        return $authenticatedTeacher;
    }

    private function teacherSections(Teacher $teacher)
    {
        return $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name', 'sections.grade_id', 'sections.classroom_id']);
    }

    private function authorizedSectionPayload(Teacher $teacher, int $sectionId)
    {
        $section = $teacher->sections()
            ->where('sections.id', $sectionId)
            ->first(['sections.id', 'sections.grade_id', 'sections.classroom_id']);

        if ($section === null) {
            throw ValidationException::withMessages([
                'section_id' => trans('validation.exists'),
            ]);
        }

        return [
            'grade_id' => (int) $section->grade_id,
            'classroom_id' => (int) $section->classroom_id,
            'section_id' => (int) $section->id,
        ];
    }

    private function ensureOwnedByTeacher(onlineClass $onlineClass, Teacher $teacher)
    {
        abort_unless(
            $onlineClass->created_by === $teacher->email,
            403
        );
    }

    private function normalizeDateTime(string $dateTime)
    {
        return Carbon::parse($dateTime)->format('Y-m-d H:i:s');
    }
}
