<div class="modal fade" id="delete_question{{ $question->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteQuestionLabel{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('questions.destroy', $question->id) }}" method="post">
            @method('DELETE')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteQuestionLabel{{ $question->id }}">
                        {{ trans('Questions_trans.delete_title') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ trans('Questions_trans.delete_warning') }} {{ $question->title }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
