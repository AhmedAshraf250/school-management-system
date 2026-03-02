<?php

namespace App\Repositories\Eloquent;

use App\Models\Fee;
use App\Repositories\Contracts\FeeRepositoryInterface;

class FeeEloRepository implements FeeRepositoryInterface
{
    public function getAllFees()
    {
        return Fee::with('grade:id,Name', 'classroom:id,name', 'student:id,name')->get();
    }

    public function getFeeById($id)
    {
        return Fee::with('grade:id,Name', 'classroom:id,name', 'student:id,name')->findOrFail($id);
    }

    public function createFee($data)
    {
        return Fee::create([
            'title' => ['en' => $data['title_en'], 'ar' => $data['title_ar']],
            'amount' => $data['amount'],
            'grade_id' => $data['grade_id'],
            'classroom_id' => $data['classroom_id'],
            'description' => $data['description'],
            'year' => $data['year'],

        ]);
    }

    public function updateFee($data, $id)
    {
        try {

            $fee = $this->getFeeById($id);
            $fee->title = ['en' => $data['title_en'], 'ar' => $data['title_ar']];
            $fee->amount = $data['amount'];
            $fee->Grade_id = $data['Grade_id'];
            $fee->Classroom_id = $data['Classroom_id'];
            $fee->description = $data['description'];
            $fee->year = $data['year'];
            $fee->save();

            return $fee;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteFee($id)
    {
        $fees = $this->getFeeById($id);
        $fees->delete();
    }
}
