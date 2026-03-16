{{-- Rollback all promotions confirmation modal --}}
<div class="modal fade" id="RollbackAllPromotionsModal" tabindex="-1"
    aria-labelledby="rollbackAllPromotionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Modal header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="rollbackAllPromotionsModalLabel">
                    {{ trans('Students_trans.rollback_all') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="modal-body">
                {{-- Rollback all form --}}
                <form action="{{ route('promotions.destroy', 0) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="page_id" value="1">

                    {{-- Confirmation text --}}
                    <h6>{{ trans('Students_trans.rollback_all_confirm') }}</h6>

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
