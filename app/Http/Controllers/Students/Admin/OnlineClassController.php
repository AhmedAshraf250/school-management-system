<?php

namespace App\Http\Controllers\Students\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreIndirectOnlineClassRequest;
use App\Http\Requests\Student\StoreOnlineClassRequest;
use App\Models\Grade;
use App\Models\onlineClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jubaer\Zoom\Facades\Zoom;

class OnlineClassController extends Controller
{
    /**
     * Load classes with relations to N+1 queries.
     */
    public function index()
    {
        $online_classes = onlineClass::query()
            ->with(['grade', 'classroom', 'section', 'user'])
            ->latest()
            ->get();

        return view('pages.online-classes.index', compact('online_classes'));
    }

    /**
     * Show direct Zoom integration form.
     */
    public function create()
    {
        $grades = Grade::query()->get();

        return view('pages.online-classes.add', compact('grades'));
    }

    /**
     * Show manual (non-integrated) class form.
     */
    public function indirectCreate() //  indirectCreate indirectCreate
    {
        $grades = Grade::query()->get();

        return view('pages.online-classes.indirect', compact('grades'));
    }

    /**
     * Create meeting via Zoom API and store locally.
     */
    public function store(StoreOnlineClassRequest $request)
    {
        try {
            $validated = $request->validated();
            $password = $validated['password'] ?? Str::upper(Str::random(8));
            $meetingPayload = [
                'topic' => $validated['topic'],
                'type' => 2,                                                // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
                'duration' => $validated['duration'],                       // in minutes
                'timezone' => config('app.timezone', 'UTC'),  // set timezone
                'password' => $password,                                    // 'set password',
                'start_time' => $validated['start_time'],                   // set start time
                'settings' => [
                    'join_before_host' => false,                            // if you want to join before host set true otherwise set false
                    'host_video' => false,                                  // if you want to start video when host join set true otherwise set false
                    'participant_video' => false,                           // if you want to start video when participants join set true otherwise set false
                    'mute_upon_entry' => true,                              // if you want to mute participants when they join the meeting set true otherwise set false
                    'waiting_room' => true,                                 // if you want to use waiting room for participants set true otherwise set false
                    'audio' => 'both',                                      // values are 'both', 'telephony', 'voip'. default is both.
                    'auto_recording' => 'none',                             // values are 'none', 'local', 'cloud'. default is none.
                    'approval_type' => 0,                                   // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
                ],
            ];

            $meetingResponse = Zoom::createMeeting($meetingPayload);
            if (($meetingResponse['status'] ?? false) !== true) {
                throw new \RuntimeException($meetingResponse['message'] ?? 'Zoom meeting could not be created.');
            }

            $meetingData = $meetingResponse['data'] ?? [];

            onlineClass::create([
                'integration' => true,
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'section_id' => $validated['section_id'],
                'user_id' => (int) Auth::id(),
                'meeting_id' => ($meetingData['id'] ?? ''),
                'topic' => $validated['topic'],
                'start_at' => $this->normalizeDateTime($meetingData['start_time'] ?? $validated['start_time']),
                'duration' => (int) ($meetingData['duration'] ?? $validated['duration']),
                'password' => ($meetingData['password'] ?? $password),
                'start_url' => ($meetingData['start_url'] ?? ''),
                'join_url' => ($meetingData['join_url'] ?? ''),
            ]);

            toastr()->success(trans('messages.success'));

            return redirect()->route('online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Create manual class using user-provided meeting details.
     */
    public function storeIndirect(StoreIndirectOnlineClassRequest $request)
    {
        try {
            $validated = $request->validated();

            onlineClass::create([
                'integration' => false,
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'section_id' => $validated['section_id'],
                'user_id' => (int) Auth::id(),
                'meeting_id' => $validated['meeting_id'],
                'topic' => $validated['topic'],
                'start_at' => $this->normalizeDateTime($validated['start_time']),
                'duration' => $validated['duration'],
                'password' => $validated['password'],
                'start_url' => $validated['start_url'],
                'join_url' => $validated['join_url'],
            ]);

            toastr()->success(trans('messages.success'));

            return redirect()->route('online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(onlineClass $onlineClass)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(onlineClass $onlineClass)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOnlineClassRequest $request, onlineClass $onlineClass)
    {
        abort(404);
    }

    /**
     * Delete local record and attempt Zoom meeting deletion for integrated classes.
     */
    public function destroy(onlineClass $onlineClass)
    {
        try {
            if ((bool) $onlineClass->integration === true) {
                $zoomDeleteResponse = Zoom::deleteMeeting($onlineClass->meeting_id);

                if (($zoomDeleteResponse['status'] ?? false) !== true) {
                    logger()->warning('Zoom meeting deletion failed.', [
                        'meeting_id' => $onlineClass->meeting_id,
                        'message' => $zoomDeleteResponse['message'] ?? 'Unknown zoom error',
                    ]);
                }
            }

            $onlineClass->delete();
            toastr()->success(trans('messages.Delete'));

            return redirect()->route('online-classes.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    private function normalizeDateTime(string $dateTime)
    {
        return Carbon::parse($dateTime)->format('Y-m-d H:i:s');
    }
}
