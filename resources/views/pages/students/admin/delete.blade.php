{{-- Student delete confirmation modal --}}
<div class="modal fade" id="Delete_Student{{ $student->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title">
                    {{ trans('Students_trans.Deleted_Student') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Delete action form --}}
                <form action="{{ route('students.destroy', $student->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <h5 style="font-family: 'Cairo', sans-serif;">
                        {{ trans('Students_trans.Deleted_Student_tilte') }}
                    </h5>
                    <input type="text" readonly value="{{ $student->name }}" class="form-control">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-dismiss="modal">
                            {{ trans('Students_trans.Close') }}
                        </button>
                        <button class="btn btn-danger btn-sm d-inline-flex align-items-center px-3 rounded-pill">
                            <i class="fa fa-trash mr-1"></i>
                            {{ trans('Students_trans.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
