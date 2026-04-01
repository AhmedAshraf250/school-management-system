<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreReceiptRequest;
use App\Http\Requests\Student\UpdateReceiptRequest;
use App\Models\Receipt;
use App\Repositories\Contracts\ReceiptsRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function __construct(private ReceiptsRepositoryInterface $receiptsRepository) {}

    public function index(Request $request): View
    {
        $isTrashView = $request->boolean('trash');
        $receipts = $this->receiptsRepository->all($isTrashView);

        return view('pages.receipts.index', compact('receipts', 'isTrashView'));
    }

    public function create(): void
    {
        abort(404);
    }

    public function store(StoreReceiptRequest $request): RedirectResponse
    {
        try {
            $this->receiptsRepository->createReceipt(
                $request->only(['student_id', 'debit', 'description'])
            );
            toastr()->success(trans('messages.success'));

            return redirect()->route('receipts.index');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function show(int $studentId): View
    {
        $student = $this->receiptsRepository->getStudentWithAccount($studentId);

        return view('pages.receipts.add', compact('student'));
    }

    public function edit(Receipt $receipt): View
    {
        $receipt = $this->receiptsRepository->find($receipt->id);
        $student = $this->receiptsRepository->getStudentWithAccount($receipt->student_id);

        return view('pages.receipts.edit', ['receipt' => $receipt, 'student' => $student]);
    }

    public function update(UpdateReceiptRequest $request, Receipt $receipt): RedirectResponse
    {
        try {
            $this->receiptsRepository->updateReceipt(
                $receipt->id,
                $request->only(['student_id', 'debit', 'description'])
            );
            toastr()->success(trans('messages.Update'));

            return redirect()->route('receipts.index');
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function destroy(Receipt $receipt): RedirectResponse
    {
        try {
            $this->receiptsRepository->deleteReceipt($receipt->id);
            toastr()->error(trans('messages.Delete'));

            return redirect()->route('receipts.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    public function restore(int $receiptId): RedirectResponse
    {
        try {
            $this->receiptsRepository->restoreReceipt($receiptId);
            toastr()->success(trans('messages.success'));

            return redirect()->route('receipts.index', ['trash' => 1]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }
}
