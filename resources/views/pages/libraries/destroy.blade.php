{{-- Library delete confirmation modal --}}
<div class="modal fade" id="delete_book{{ $book->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('libraries.destroy', $book->id) }}" method="post">
            @method('delete')
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('libraries_trans.delete_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>
                        {{ trans('libraries_trans.delete_confirmation') }}
                        <span class="text-danger">{{ $book->title }}</span>
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ trans('libraries_trans.close') }}
                    </button>
                    <button type="submit" class="btn btn-danger">{{ trans('libraries_trans.delete') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
