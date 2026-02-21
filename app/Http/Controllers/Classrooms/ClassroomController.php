<?php

namespace App\Http\Controllers\Classrooms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\HttpCache\Store;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::select('id', 'Name')->get();
        $classrooms = Classroom::with('grade:id,Name')->get();

        return view('pages.classrooms.classrooms', compact('classrooms', 'grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        // Validation
        try {
            DB::beginTransaction();

            // Loop through repeater data
            foreach ($request->List_Classes as $class) {
                Classroom::create([
                    'name' => [
                        'ar' => $class['Name'],
                        'en' => $class['Name_class_en']
                    ],
                    'grade_id' => $class['grade_id'],
                ]);
            }

            DB::commit();
            toastr()->success(trans('messages.success'));

            return redirect()->route('classrooms.index');
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error(trans('messages.error') . ': ' . $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {

        $classrooms = Classroom::with('grade:id,Name')->get();

        return view('pages.classrooms.edit', compact('classrooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);

            $classroom->update([
                'name' => [
                    'ar' => $request->Name,
                    'en' => $request->Name_en,
                ],
                'grade_id' => $request->grade_id,
            ]);

            toastr()->success(trans('messages.update'));

            return redirect()->route('classrooms.index');
        } catch (\Exception $e) {
            toastr()->error(trans('messages.error') . ': ' . $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->delete();

            toastr()->success(trans('messages.Delete'));

            return redirect()->route('classrooms.index');
        } catch (\Exception $e) {
            toastr()->error(trans('messages.error') . ': ' . $e->getMessage());

            return redirect()->back();
        }
    }


    public function delete_all(Request $request)
    {

        $ids = array_filter(
            explode(',', $request->delete_all_id),
            fn($id) => is_numeric(trim($id))
        );

        // $ids = explode(',', $request->delete_all_id);`
        if (empty($ids)) {
            toastr()->error(trans('messages.error'));
            return redirect()->back();
        }

        Classroom::whereIn('id', $ids)->delete();

        toastr()->success(trans('messages.Delete'));
        return redirect()->route('classrooms.index');
    }


    public function filter_classes(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
        ]);

        $grades = Grade::select('id', 'Name')->get();
        $classrooms = Classroom::with('grade:id,Name')
            ->where('grade_id', $request->grade_id)
            ->get();

        return view('pages.classrooms.classrooms', compact('grades', 'classrooms'));
    }
}
