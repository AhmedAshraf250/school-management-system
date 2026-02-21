<div class="modal fade" id="add-grade-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title">
                    {{ trans('Grades_trans.add_Grade') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="Name" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_ar') }}:
                            </label>
                            <input id="Name" type="text" name="Name" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="Name_en" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_en') }}:
                            </label>
                            <input type="text" class="form-control" name="Name_en" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="Notes">{{ trans('Grades_trans.Notes') }}:</label>
                        <textarea class="form-control" name="Notes" id="Notes" rows="3"></textarea>
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
