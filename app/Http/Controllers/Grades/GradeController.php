<?php

namespace App\Http\Controllers\Grades;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradesRequest;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Grades = Grade::all();
        return view('pages.Grades.Grades', compact('Grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradesRequest $request)
    {
        try {
            $validated = $request->validated();

            $Grade = new Grade();
            /*
                $translations = [
                    'en' => $request->Name_en,
                    'ar' => $request->Name
                ];
                $Grade->setTranslations('Name', $translations);
             */
            $Grade->Name = [
                'en' => $validated['Name_en'],
                'ar' => $validated['Name']
            ];
            $Grade->Notes = $validated['Notes'];
            $Grade->save();

            toastr()->success(trans('messages.success'));
            return redirect()->route('Grades.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradesRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $grade = Grade::findOrFail($id);

            if ($request->has('Name')) {
                $grade->setTranslation('Name', 'ar', $validated['Name']);
            }

            if ($request->has('Name_en')) {
                $grade->setTranslation('Name', 'en', $validated['Name_en']);
            }

            $grade->Notes = $validated['Notes'] ?? $grade->Notes;

            $grade->save();

            toastr()->success(trans('messages.Update'));
            return redirect()->route('Grades.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Grade::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Grades.index');
    }
}
