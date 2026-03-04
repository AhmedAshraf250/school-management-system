{{-- Delete Payment Modal --}}
<div class="modal fade" id="Delete_payment{{ $payment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                    {{ trans('fees_trans.delete_payment_voucher') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body + Delete Form --}}
            <div class="modal-body">
                <form action="{{ route('student-payments.destroy', $payment->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="id" value="{{ $payment->id }}">
                    <h5 style="font-family: 'Cairo', sans-serif;">{{ trans('fees_trans.delete_confirmation') }}</h5>

                    {{-- Modal Footer Actions --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ trans('Students_trans.Close') }}
                        </button>
                        <button class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
