<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreFeeRequest;
use App\Repositories\Contracts\FeeRepositoryInterface;
use App\Repositories\Eloquent\StaticDataEloRepository;

class FeeController extends Controller
{
    protected $fee;

    public function __construct(FeeRepositoryInterface $fee, protected StaticDataEloRepository $staticData)
    {
        $this->fee = $fee;
    }

    public function index()
    {
        $fees = $this->fee->getAllFees();

        return view('pages.fees.index', ['fees' => $fees]);
    }

    public function create()
    {
        $grades = $this->staticData->getGrades();

        return view('pages.fees.add', ['grades' => $grades]);
    }

    public function show($id) {}

    public function store(StoreFeeRequest $request)
    {
        $this->fee->createFee($request->validated());

        return redirect()->route('fees.index')->with('success', '');
    }

    public function edit($id)
    {
        $fee = $this->fee->getFeeById($id);
        $grades = $this->staticData->getGrades();

        return view('pages.fees.edit', ['fee' => $fee, 'grades' => $grades]);
    }

    public function update(StoreFeeRequest $request, $id)
    {
        $this->fee->updateFee($request->validated(), $id);
        $this->flashSuccess(trans('messages.success'));

        return redirect()->route('fees.create');
    }

    public function destroy($id)
    {
        return $this->fee->deleteFee($id);
    }
}
