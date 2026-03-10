<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreLibraryRequest;
use App\Http\Requests\Library\UpdateLibraryRequest;
use App\Models\Library;
use App\Models\Teacher;
use App\Repositories\Contracts\LibraryRepositoryInterface;
use App\Repositories\Contracts\StaticDataRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryController extends Controller
{
    public function __construct(
        protected LibraryRepositoryInterface $libraryRepository,
        protected StaticDataRepositoryInterface $staticData
    ) {}

    public function index(): View
    {
        $books = $this->libraryRepository->getAllBooks();

        return view('pages.libraries.index', compact('books'));
    }

    public function create(): View
    {
        $grades = $this->staticData->getGrades();
        $teachers = Teacher::query()->select('id', 'name')->get();

        return view('pages.libraries.create', compact('grades', 'teachers'));
    }

    public function store(StoreLibraryRequest $request): RedirectResponse
    {
        try {
            $this->libraryRepository->store($request->validated());
            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('libraries.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function edit(Library $library): View
    {
        $book = $this->libraryRepository->getById($library->id);
        $grades = $this->staticData->getGrades();
        $teachers = Teacher::query()->select('id', 'name')->get();
        $classrooms = $this->libraryRepository->getClassroomsByGrade((int) $book->grade_id);
        $sections = $this->libraryRepository->getSectionsByClassroom((int) $book->classroom_id);

        return view('pages.libraries.edit', compact('book', 'grades', 'teachers', 'classrooms', 'sections'));
    }

    public function update(UpdateLibraryRequest $request, Library $library): RedirectResponse
    {
        try {
            $this->libraryRepository->update($request->validated(), $library);
            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('libraries.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function destroy(Library $library): RedirectResponse
    {
        try {
            $this->libraryRepository->delete($library);
            $this->flashError(trans('messages.Delete'));

            return redirect()->route('libraries.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function download(Library $library): BinaryFileResponse
    {
        $disk = Storage::disk('public');

        return response()->download($disk->path($library->path), $library->file_name);
    }
}
