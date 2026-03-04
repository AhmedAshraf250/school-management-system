<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\ProcessingFee;
use App\Repositories\Contracts\ProcessingFeeRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcessingFeeController extends Controller
{
    public function __construct(private ProcessingFeeRepositoryInterface $processingFeeRepository) {}

    public function index(): View
    {
        $processingFees = $this->processingFeeRepository->all();

        return view('pages.processing-fees.index', compact('processingFees'));
    }

    public function create(): void
    {
        abort(404);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->processingFeeRepository->createProcessingFee(
                $request->only(['student_id', 'debit', 'description'])
            );
            toastr()->success(trans('messages.success'));

            return redirect()->route('processing-fees.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function show(int $studentId): View
    {
        $student = $this->processingFeeRepository->getStudentWithAccount($studentId);

        return view('pages.processing-fees.add', compact('student'));
    }

    public function edit(ProcessingFee $processingFee): View
    {
        $processingFee = $this->processingFeeRepository->find($processingFee->id);

        return view('pages.processing-fees.edit', compact('processingFee'));
    }

    public function update(Request $request, ProcessingFee $processingFee): RedirectResponse
    {
        try {
            $this->processingFeeRepository->updateProcessingFee(
                $processingFee->id,
                $request->only(['student_id', 'debit', 'description'])
            );
            toastr()->success(trans('messages.Update'));

            return redirect()->route('processing-fees.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function destroy(ProcessingFee $processingFee): RedirectResponse
    {
        try {
            $this->processingFeeRepository->deleteProcessingFee($processingFee->id);
            toastr()->error(trans('messages.Delete'));

            return redirect()->route('processing-fees.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }
}
