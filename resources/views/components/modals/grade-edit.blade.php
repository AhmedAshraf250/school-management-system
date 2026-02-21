<div class="modal fade" id="edit-grade-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title">
                    {{ trans('Grades_trans.edit_Grade') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="edit-modal-form" method="post">
                    @method('PATCH')
                    @csrf

                    <input type="hidden" id="edit-modal-id" name="id">

                    <div class="row">
                        <div class="col">
                            <label for="edit-modal-name-ar" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_ar') }}:
                            </label>
                            <input id="edit-modal-name-ar" type="text" name="Name" class="form-control" required>
                        </div>

                        <div class="col">
                            <label for="edit-modal-name-en" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_en') }}:
                            </label>
                            <input id="edit-modal-name-en" type="text" class="form-control" name="Name_en" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="edit-modal-notes">{{ trans('Grades_trans.Notes') }}:</label>
                        <textarea class="form-control" name="Notes" id="edit-modal-notes" rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ trans('Grades_trans.Close') }}
                        </button>
                        <button type="submit" class="btn btn-success">
                            {{ trans('Grades_trans.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
