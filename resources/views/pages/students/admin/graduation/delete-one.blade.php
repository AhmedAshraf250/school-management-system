<div class="modal fade" id="RollbackOneGraduationModal{{ $graduation->id }}" tabindex="-1"
    aria-labelledby="rollbackOneGraduationModalLabel{{ $graduation->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rollbackOneGraduationModalLabel{{ $graduation->id }}">
                    {{ trans('Students_trans.graduation_rollback_one') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('graduates.destroy', $graduation->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="graduation_id" value="{{ $graduation->id }}">

                    <h6>
                        {{ trans('Students_trans.graduation_rollback_confirm') }}
                        <strong>{{ $graduation->student?->name }}</strong>؟
                    </h6>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                        <button class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
