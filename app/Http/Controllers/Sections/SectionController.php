<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

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
            'sections.teachers:id,name',
        ])->get();

        $teachers = Teacher::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('pages.sections.sections', ['grades' => $grades, 'teachers' => $teachers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated): void {
                $section = Section::query()->create([
                    'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                    'grade_id' => $validated['grade_id'],
                    'classroom_id' => $validated['classroom_id'],
                    'status' => true,
                ]);

                $section->teachers()->sync($validated['teacher_id']);
            });

            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('sections.index');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSectionRequest $request, Section $section): RedirectResponse
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($request, $section, $validated): void {
                $section->update([
                    'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                    'grade_id' => $validated['grade_id'],
                    'classroom_id' => $validated['classroom_id'],
                    'status' => $request->boolean('status'),
                ]);

                $section->teachers()->sync($validated['teacher_id'] ?? []);
            });
            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('sections.index');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();
        $this->flashError(trans('messages.Delete'));

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
