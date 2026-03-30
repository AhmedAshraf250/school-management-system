<div>
    {{-- Quiz attempt interactive shell --}}
    <div class="quiz-attempt-shell">
        {{-- Progress header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h5 class="mb-1">{{ trans('Quizzes_trans.student_attempt_progress_title') }}</h5>
                <small class="text-muted">
                    {{ trans('Quizzes_trans.student_attempt_progress_value', ['answered' => $this->answeredCount, 'total' => count($questionItems)]) }}
                </small>
            </div>
            <div class="quiz-progress-pill">
                {{ trans('Quizzes_trans.student_question_index', ['current' => $currentIndex + 1, 'total' => count($questionItems)]) }}
            </div>
        </div>

        {{-- Question quick navigation --}}
        <div class="quiz-question-nav mb-3">
            @foreach ($questionItems as $index => $question)
                @php
                    $questionId = $question['id'];
                    $hasAnswer = filled($selectedAnswers[$questionId] ?? null);
                @endphp
                <button type="button" class="btn btn-sm {{ $currentIndex === $index ? 'btn-primary' : ($hasAnswer ? 'btn-success' : 'btn-outline-secondary') }}"
                    wire:click="goToQuestion({{ $index }})" wire:key="quiz-question-nav-{{ $questionId }}">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>

        @if (filled($navigationError))
            <div class="alert alert-danger py-2">{{ $navigationError }}</div>
        @endif

        {{-- Single question card --}}
        <div class="card border-0 shadow-sm mb-3" wire:key="current-question-{{ $activeQuestion['id'] }}">
            <div class="card-body">
                <h6 class="mb-3">
                    {{ trans('Quizzes_trans.student_question_title') }}
                    {{ $currentIndex + 1 }}
                </h6>

                <p class="mb-3">{{ $activeQuestion['title'] }}</p>

                <div class="quiz-answer-grid">
                    @foreach ($activeQuestion['answers'] as $answer)
                        <label class="quiz-answer-item" wire:key="answer-{{ $activeQuestion['id'] }}-{{ md5($answer) }}">
                            <input type="radio"
                                wire:model.live="selectedAnswers.{{ $activeQuestion['id'] }}"
                                value="{{ $answer }}">
                            <span>{{ $answer }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Navigation actions --}}
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="previousQuestion"
                @disabled($currentIndex === 0)>
                {{ trans('Quizzes_trans.student_previous_question') }}
            </button>

            <div class="d-flex flex-wrap gap-2">
                @if ($currentIndex < count($questionItems) - 1)
                    <button type="button" class="btn btn-primary" wire:click="nextQuestion">
                        {{ trans('Quizzes_trans.student_save_next_question') }}
                    </button>
                @else
                    <button type="button" class="btn btn-success"
                        wire:click="submitQuiz"
                        @disabled($this->answeredCount < count($questionItems))
                        wire:confirm="{{ trans('Quizzes_trans.student_submit_confirmation') }}">
                        {{ trans('Quizzes_trans.student_submit_quiz') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .quiz-attempt-shell {
            --quiz-accent: #1f7a8c;
            --quiz-accent-soft: #e6f4f1;
            --quiz-ink: #0f172a;
            --quiz-border: #d6e4ec;
        }

        .quiz-progress-pill {
            background: linear-gradient(120deg, #1f7a8c, #2a9d8f);
            color: #fff;
            border-radius: 999px;
            padding: 0.4rem 0.9rem;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .quiz-question-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            background: var(--quiz-accent-soft);
            padding: 0.75rem;
            border-radius: 0.8rem;
            border: 1px solid var(--quiz-border);
        }

        .quiz-answer-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .quiz-answer-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: #fff;
            border: 1px solid var(--quiz-border);
            border-radius: 0.7rem;
            padding: 0.75rem 0.85rem;
            margin: 0;
            color: var(--quiz-ink);
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .quiz-answer-item:hover {
            border-color: var(--quiz-accent);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            transform: translateY(-1px);
        }

        .quiz-answer-item input[type="radio"] {
            margin-top: 0;
        }

        @media (min-width: 768px) {
            .quiz-answer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush
