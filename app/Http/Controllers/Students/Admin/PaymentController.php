<?php

namespace App\Http\Controllers\Students\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentRepositoryInterface $paymentRepository) {}

    public function index(Request $request)
    {
        $isTrashView = $request->boolean('trash');
        $payments = $this->paymentRepository->all($isTrashView);

        return view('pages.payments.index', compact('payments', 'isTrashView'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        try {
            $this->paymentRepository->createPayment($request->only(['student_id', 'debit', 'description']));
            toastr()->success(trans('messages.success'));

            return redirect()->route('student-payments.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($studentId)
    {
        $student = $this->paymentRepository->getStudentWithAccount($studentId);

        return view('pages.payments.add', compact('student'));
    }

    public function edit(Payment $student_payment)
    {
        $payment = $this->paymentRepository->find($student_payment->id);
        $student = $this->paymentRepository->getStudentWithAccount($payment->id);

        return view('pages.payments.edit', compact('payment', 'student'));
    }

    public function update(Request $request, Payment $payment)
    {
        try {
            $this->paymentRepository->updatePayment($payment->id, $request->only(['student_id', 'debit', 'description']));
            toastr()->success(trans('messages.Update'));

            return redirect()->route('student-payments.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Payment $student_payment)
    {
        try {
            $this->paymentRepository->deletePayment($student_payment->id);
            toastr()->error(trans('messages.Delete'));

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function restore(int $paymentId)
    {
        try {
            $this->paymentRepository->restorePayment($paymentId);
            toastr()->success(trans('messages.success'));

            return redirect()->route('student-payments.index', ['trash' => 1]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
