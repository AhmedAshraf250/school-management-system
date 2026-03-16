<?php

namespace App\Http\Controllers\Students\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RollbackPromotionRequest;
use App\Http\Requests\Student\StorePromotionRequest;
use App\Services\Students\PromotionService;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function __construct(protected PromotionService $promotionService) {}

    public function index()
    {
        $promotions = $this->promotionService->getPromotionHistory();

        return view('pages.students.admin.promotion.management', compact('promotions'));
    }

    public function create()
    {
        $formData = $this->promotionService->getPromotionFormData();

        return view('pages.students.admin.promotion.index', $formData);
    }

    public function store(StorePromotionRequest $request)
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

    public function destroy(RollbackPromotionRequest $request, string $promotion)
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
