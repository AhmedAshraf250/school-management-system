{{-- Rollback one promotion confirmation modal --}}
<div class="modal fade" id="RollbackOnePromotionModal{{ $promotion->id }}" tabindex="-1"
    aria-labelledby="rollbackOnePromotionModalLabel{{ $promotion->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Modal header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="rollbackOnePromotionModalLabel{{ $promotion->id }}">
                    {{ trans('Students_trans.rollback_one') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="modal-body">
                {{-- Rollback one form --}}
                <form action="{{ route('promotions.destroy', $promotion->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="promotion_id" value="{{ $promotion->id }}">

                    {{-- Confirmation text --}}
                    <h6>
                        {{ trans('Students_trans.rollback_one_confirm') }}
                        <strong>{{ $promotion->student?->name }}</strong>؟
                    </h6>

                    {{-- Modal actions --}}
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
