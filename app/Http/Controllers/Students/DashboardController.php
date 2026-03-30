<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('pages.students.dashboard.dashboard');
    }

    public function calendar(): View
    {
        return view('pages.students.dashboard.calendar');
    }

    public function profile(): View
    {
        /** @var Student|null $student */
        $student = auth()->guard('student')->user();
        abort_unless($student instanceof Student, 403);
        $student->load([
            'gender:id,name',
            'nationality:id,name',
            'bloodType:id,name',
            'grade:id,Name',
            'classroom:id,name',
            'section:id,name',
            'guardian:id,father_name,mother_name',
        ]);

        return view('pages.students.dashboard.profile', [
            'student' => $student,
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var Student|null $student */
        $student = auth()->guard('student')->user();
        abort_unless($student instanceof Student, 403);

        $validatedData = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:student'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $student->update([
            'password' => Hash::make($validatedData['password']),
        ]);

        toastr()->success(trans('messages.success'));

        return redirect()->route('student.profile')->with('status', 'password-updated');
    }
}
