<?php

namespace App\Http\Controllers\Students\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreFeeInvoiceRequest;
use App\Http\Requests\Student\UpdateFeeInvoiceRequest;
use App\Models\FeeInvoice;
use App\Repositories\Contracts\FeeInvoicesRepositoryInterface;
use Illuminate\Validation\ValidationException;

class FeeInvoiceController extends Controller
{
    public function __construct(private FeeInvoicesRepositoryInterface $feeInvoiceRepository) {}

    public function index()
    {
        $feeInvoices = $this->feeInvoiceRepository->all();

        return view('pages.fee-invoices.index', compact('feeInvoices'));
    }

    public function create(): void
    {
        abort(404);
    }

    public function store(StoreFeeInvoiceRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->feeInvoiceRepository->createInvoices($validated['list_fees'] ?? []);
            toastr()->success(trans('messages.success'));

            return redirect()->route('fee-invoices.index');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function show(int $studentId)
    {
        $student = $this->feeInvoiceRepository->getStudentWithAccount($studentId);
        $fees = $this->feeInvoiceRepository->availableFees((int) $student->grade_id, (int) $student->classroom_id);

        return view('pages.fee-invoices.add', compact('student', 'fees'));
    }

    public function edit(FeeInvoice $feeInvoice)
    {
        $feeInvoice = $this->feeInvoiceRepository->find($feeInvoice->id);
        $fees = $this->feeInvoiceRepository->availableFees((int) $feeInvoice->grade_id, (int) $feeInvoice->classroom_id);

        return view('pages.fee-invoices.edit', compact('feeInvoice', 'fees'));
    }

    public function update(UpdateFeeInvoiceRequest $request, FeeInvoice $feeInvoice)
    {
        try {
            $validated = $request->validated();
            $this->feeInvoiceRepository->updateInvoice(
                $feeInvoice->id,
                [
                    'student_id' => $validated['student_id'],
                    'fee_id' => $validated['fee_id'],
                    'description' => $validated['description'] ?? null,
                ]
            );
            toastr()->success(trans('messages.Update'));

            return redirect()->route('fee-invoices.index');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function destroy(FeeInvoice $feeInvoice)
    {
        try {
            $this->feeInvoiceRepository->deleteInvoice($feeInvoice->id);
            toastr()->success(trans('messages.Delete'));

            return redirect()->route('fee-invoices.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }
}
