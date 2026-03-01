<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RollbackPromotionRequest;
use App\Http\Requests\Student\StorePromotionRequest;
use App\Services\Students\PromotionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function __construct(protected PromotionService $promotionService) {}

    public function index(): View
    {
        $promotions = $this->promotionService->getPromotionHistory();

        return view('pages.students.promotion.management', compact('promotions'));
    }

    public function create(): View
    {
        $formData = $this->promotionService->getPromotionFormData();

        return view('pages.students.promotion.index', $formData);
    }

    public function store(StorePromotionRequest $request): RedirectResponse
    {
        try {
            $this->promotionService->promote($request->validated(), Auth::id());
            $this->flashSuccess(trans('Students_trans.promotions_done'));

            return redirect()->route('promotions.index');
        } catch (\DomainException $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['promotion' => trans($exception->getMessage())]);
        }
    }

    public function destroy(RollbackPromotionRequest $request, string $promotion): RedirectResponse
    {
        $validated = $request->validated();
        $isRollbackAll = (int) ($validated['page_id'] ?? 0) === 1;

        if ($isRollbackAll) {
            $this->promotionService->rollbackAll();

            $this->flashSuccess(trans('Students_trans.rollback_all_done'));

            return redirect()->route('promotions.index');
        }

        $this->promotionService->rollbackOne((int) ($validated['promotion_id'] ?? $promotion));

        $this->flashSuccess(trans('Students_trans.rollback_one_done'));

        return redirect()->route('promotions.index');
    }
}
