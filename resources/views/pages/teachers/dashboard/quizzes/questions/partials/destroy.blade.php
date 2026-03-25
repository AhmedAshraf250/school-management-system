<form method="POST" action="{{ route('teacher.quizzes.questions.destroy', [$quiz->id, $question->id]) }}"
    class="d-inline"
    onsubmit="return confirm('{{ trans('Questions_trans.delete_warning') }} {{ $question->title }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('Questions_trans.delete_title') }}">
        <i class="fa fa-trash"></i>
    </button>
</form>
