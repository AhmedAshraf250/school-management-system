<div class="modal fade" id="delete-grade-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title">
                    {{ trans('Grades_trans.delete_Grade') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="delete-modal-form" method="post">
                    @method('DELETE')
                    @csrf

                    <input type="hidden" id="delete-modal-id" name="id">

                    <div class="alert alert-warning" role="alert">
                        {{ trans('Grades_trans.Warning_Grade') }}
                        <br>
                        <strong id="delete-modal-name"></strong>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ trans('Grades_trans.Close') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ trans('Grades_trans.Delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
