<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::with(['quiz:id,name'])->latest()->get();

        return view('pages.questions.index', compact('questions'));
    }

    public function create(): View
    {
        $quizzes = Quiz::query()->select('id', 'name')->latest()->get();

        return view('pages.questions.create', compact('quizzes'));
    }

    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            Question::query()->create([
                'title' => $validated['title'],
                'answers' => $validated['answers'],
                'right_answer' => $validated['right_answer'],
                'score' => $validated['score'],
                'quiz_id' => $validated['quiz_id'],
            ]);

            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('questions.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function show(Question $question): RedirectResponse
    {
        return redirect()->route('questions.edit', $question);
    }

    public function edit(Question $question): View
    {
        $quizzes = Quiz::query()->select('id', 'name')->latest()->get();

        return view('pages.questions.edit', compact('question', 'quizzes'));
    }

    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $question->update([
                'title' => $validated['title'],
                'answers' => $validated['answers'],
                'right_answer' => $validated['right_answer'],
                'score' => $validated['score'],
                'quiz_id' => $validated['quiz_id'],
            ]);

            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('questions.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();
        $this->flashError(trans('messages.Delete'));

        return redirect()->route('questions.index');
    }
}
