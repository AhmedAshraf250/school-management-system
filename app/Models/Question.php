<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'title',
        'answers',
        'right_answer',
        'score',
        'quiz_id',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'question_id');
    }

    public function answerOptions(): array
    {
        return collect(preg_split('/[\n,]+/', (string) $this->answers))
            ->map(fn (?string $answer): string => trim((string) $answer))
            ->filter()
            ->values()
            ->all();
    }
}
