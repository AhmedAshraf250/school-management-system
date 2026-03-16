<?php

namespace App\Http\Controllers\Students\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RollbackGraduationRequest;
use App\Http\Requests\Student\StoreBulkGraduationRequest;
use App\Http\Requests\Student\StoreGraduationRequest;
use App\Services\Students\GraduationService;
use Illuminate\Support\Facades\Auth;

class GraduationController extends Controller
{
    public function __construct(protected GraduationService $graduationService) {}

    public function index()
    {
        $graduations = $this->graduationService->getGraduationHistory();
        $formData = $this->graduationService->getGraduationFormData();

        return view('pages.students.admin.graduation.management', array_merge($formData, compact('graduations')));
    }

    public function store(StoreGraduationRequest $request)
    {
        try {
            $this->graduationService->graduate($request->validated(), Auth::id());
            $this->flashSuccess(trans('Students_trans.graduation_done'));

            return redirect()->route('graduates.index');
        } catch (\DomainException $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['graduation' => trans($exception->getMessage())]);
        }
    }

    public function destroy(RollbackGraduationRequest $request, string $graduate)
    {
        $validated = $request->validated();

        $this->graduationService->rollbackOne((int) ($validated['graduation_id'] ?? $graduate));

        $this->flashSuccess(trans('Students_trans.graduation_rollback_done'));

        return redirect()->route('graduates.index');
    }

    public function graduateBatch(StoreBulkGraduationRequest $request)
    {
        try {
            $count = $this->graduationService->graduateBatch($request->validated(), Auth::id());
            $this->flashSuccess(trans('Students_trans.graduation_batch_done', ['count' => $count]));

            return redirect()->route('graduates.index');
        } catch (\DomainException $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['graduation_batch' => trans($exception->getMessage())]);
        }
    }
}
