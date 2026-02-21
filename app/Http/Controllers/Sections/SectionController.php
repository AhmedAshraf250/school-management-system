<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $grades = Grade::with([
            'sections:id,name,grade_id,classroom_id,status',
            'sections.classroom:id,name',
        ])->get();

        return view('pages.sections.sections', ['grades' => $grades]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {

        try {
            $validated = $request->validated();
            $sections = new Section;

            $sections->name = ['ar' => $validated['name_ar'], 'en' => $validated['name_en']];
            $sections->grade_id = $validated['grade_id'];
            $sections->classroom_id = $validated['classroom_id'];
            $sections->status = 1;
            $sections->save();
            toastr()->success(trans('messages.success'));

            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSectionRequest $request, Section $section)
    {

        try {
            $validated = $request->validated();

            $section->name = ['ar' => $validated['name_ar'], 'en' => $validated['name_en']];
            $section->grade_id = $validated['grade_id'];
            $section->classroom_id = $validated['classroom_id'];
            $section->status = $request->boolean('status');

            $section->save();
            toastr()->success(trans('messages.Update'));

            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        toastr()->error(trans('messages.Delete'));

        return redirect()->route('sections.index');
    }

    public function getclasses(int $id): JsonResponse
    {
        $list_classes = Classroom::query()
            ->where('grade_id', $id)
            ->pluck('name', 'id');

        return response()->json($list_classes);
    }
}
