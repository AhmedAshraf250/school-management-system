<?php

namespace App\Livewire\Students\Quizzes;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TakeQuiz extends Component
{
    #[Locked]
    public int $attemptId;

    public int $currentIndex = 0;

    public array $selectedAnswers = [];

    public array $questionItems = [];

    public array $activeQuestion = [];

    public ?string $navigationError = null;

    public function mount(QuizAttempt $attempt): void
    {
        $student = $this->authenticatedStudent();
        $attempt->loadMissing('quiz.questions');
        abort_unless((int) $attempt->student_id === (int) $student->id, 403);
        abort_unless($attempt->status === QuizAttempt::STATUS_IN_PROGRESS, 403);
        $this->attemptId = (int) $attempt->id;

        $this->questionItems = $attempt->quiz->questions
            ->sortBy('id')
            ->values()
            ->map(fn (Question $question): array => [
                'id' => (int) $question->id,
                'title' => (string) $question->title,
                'answers' => $question->answerOptions(),
                'score' => (int) $question->score,
            ])
            ->all();

        abort_if(count($this->questionItems) === 0, 404);

        $savedAnswers = QuizAttemptAnswer::query()
            ->where('quiz_attempt_id', $attempt->id)
            ->pluck('selected_answer', 'question_id')
            ->all();

        foreach ($savedAnswers as $questionId => $selectedAnswer) {
            $this->selectedAnswers[(int) $questionId] = (string) $selectedAnswer;
        }

        $this->currentIndex = $this->firstUnansweredIndex();
        $this->syncActiveQuestion();
        $this->syncMaxScore();
    }

    public function previousQuestion(): void
    {
        $this->navigationError = null;

        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->syncActiveQuestion();
        }
    }

    public function nextQuestion(): void
    {
        if (! $this->hasAnswerForCurrentQuestion()) {
            $this->navigationError = trans('Quizzes_trans.student_answer_required_before_next');

            return;
        }

        $this->navigationError = null;
        $this->saveCurrentAnswer();

        if ($this->currentIndex < ($this->totalQuestions() - 1)) {
            $this->currentIndex++;
            $this->syncActiveQuestion();
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->totalQuestions()) {
            $this->navigationError = null;
            $this->currentIndex = $index;
            $this->syncActiveQuestion();
        }
    }

    public function submitQuiz(): void
    {
        if ($this->answeredCount < $this->totalQuestions()) {
            $this->navigationError = trans('Quizzes_trans.student_submit_requires_all_answers');
            $this->currentIndex = $this->firstUnansweredIndex();
            $this->syncActiveQuestion();

            return;
        }

        $this->navigationError = null;
        $this->saveCurrentAnswer();

        $attempt = QuizAttempt::query()->findOrFail($this->attemptId);
        if ($attempt->status !== QuizAttempt::STATUS_IN_PROGRESS) {
            $this->redirectRoute('student.quizzes.show', $attempt->quiz_id);

            return;
        }

        DB::transaction(function () use ($attempt): void {
            $lockedAttempt = QuizAttempt::query()->lockForUpdate()->findOrFail($attempt->id);
            if ($lockedAttempt->status !== QuizAttempt::STATUS_IN_PROGRESS) {
                return;
            }

            $totalScore = (int) QuizAttemptAnswer::query()
                ->where('quiz_attempt_id', $lockedAttempt->id)
                ->sum('score_awarded');

            $lockedAttempt->update([
                'status' => QuizAttempt::STATUS_SUBMITTED,
                'submitted_at' => now(),
                'total_score' => max(0, $totalScore),
            ]);
        });

        $this->redirectRoute('student.quizzes.show', $attempt->quiz_id);
    }

    public function render()
    {
        return view('livewire.students.quizzes.take-quiz');
    }

    public function getAnsweredCountProperty(): int
    {
        return collect($this->questionItems)
            ->filter(function (array $question): bool {
                $selectedAnswer = trim((string) ($this->selectedAnswers[$question['id']] ?? ''));

                return $selectedAnswer !== '';
            })
            ->count();
    }

    private function saveCurrentAnswer()
    {
        $attempt = QuizAttempt::query()->findOrFail($this->attemptId);
        if ($attempt->status !== QuizAttempt::STATUS_IN_PROGRESS) {
            return;
        }

        $currentQuestion = $this->activeQuestion;
        $questionId = (int) $currentQuestion['id'];

        $selectedAnswer = trim((string) ($this->selectedAnswers[$questionId] ?? ''));
        if ($selectedAnswer === '') {
            return;
        }

        $allowedAnswers = collect($currentQuestion['answers'])
            ->map(fn (string $answer): string => trim($answer))
            ->filter()
            ->values();

        if (! $allowedAnswers->contains($selectedAnswer)) {
            $this->markAttemptAsBlocked($attempt, 'invalid_answer_payload');

            return;
        }

        DB::transaction(function () use ($attempt, $questionId, $selectedAnswer): void {
            $lockedAttempt = QuizAttempt::query()->lockForUpdate()->findOrFail($attempt->id);
            if ($lockedAttempt->status !== QuizAttempt::STATUS_IN_PROGRESS) {
                return;
            }

            $question = Question::query()
                ->where('quiz_id', $lockedAttempt->quiz_id)
                ->findOrFail($questionId);

            if (! in_array($selectedAnswer, $question->answerOptions(), true)) {
                $this->markAttemptAsBlocked($lockedAttempt, 'answer_not_in_question_options');

                return;
            }

            $isCorrect = trim($selectedAnswer) === trim((string) $question->right_answer);
            $maxScore = (int) $question->score;
            $scoreAwarded = $isCorrect ? $maxScore : 0;

            QuizAttemptAnswer::query()->updateOrCreate(
                [
                    'quiz_attempt_id' => $lockedAttempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect,
                    'score_awarded' => $scoreAwarded,
                    'max_score' => $maxScore,
                    'answered_at' => now(),
                ]
            );

            $lockedAttempt->update([
                'total_score' => (int) QuizAttemptAnswer::query()
                    ->where('quiz_attempt_id', $lockedAttempt->id)
                    ->sum('score_awarded'),
            ]);
        });
    }

    private function hasAnswerForCurrentQuestion(): bool
    {
        $questionId = (int) $this->activeQuestion['id'];
        $selectedAnswer = trim((string) ($this->selectedAnswers[$questionId] ?? ''));

        return $selectedAnswer !== '';
    }

    private function markAttemptAsBlocked(QuizAttempt $attempt, string $reason): void
    {
        $attempt->update([
            'status' => QuizAttempt::STATUS_BLOCKED,
            'blocked_at' => now(),
            'blocked_reason' => $reason,
            'submitted_at' => now(),
            'total_score' => 0,
            'violations_count' => $attempt->violations_count + 1,
        ]);

        $this->redirectRoute('student.quizzes.show', $attempt->quiz_id);
    }

    private function firstUnansweredIndex(): int
    {
        $firstUnansweredIndex = collect($this->questionItems)
            ->search(function (array $question): bool {
                $selectedAnswer = trim((string) ($this->selectedAnswers[$question['id']] ?? ''));

                return $selectedAnswer === '';
            });

        if (is_int($firstUnansweredIndex)) {
            return $firstUnansweredIndex;
        }

        return 0;
    }

    private function totalQuestions(): int
    {
        return count($this->questionItems);
    }

    private function syncMaxScore(): void
    {
        $maxScore = (int) collect($this->questionItems)->sum('score');
        QuizAttempt::query()->whereKey($this->attemptId)->update(['max_score' => $maxScore]);
    }

    private function syncActiveQuestion(): void
    {
        $this->activeQuestion = $this->questionItems[$this->currentIndex] ?? [];
    }

    private function authenticatedStudent(): Student
    {
        $authenticatedStudent = auth()->guard('student')->user();

        if (! $authenticatedStudent instanceof Student) {
            abort(403);
        }

        return $authenticatedStudent;
    }
}
