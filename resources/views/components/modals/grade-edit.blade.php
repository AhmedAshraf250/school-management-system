@props(['grade'])

<div class="modal fade" id="edit{{ $grade->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                <form action="{{ route('Grades.update', $grade->id) }}" method="post">
                    @method('PATCH')
                    @csrf

                    <div class="row">
                        <div class="col">
                            <label for="Name{{ $grade->id }}" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_ar') }}:
                            </label>
                            <input id="Name{{ $grade->id }}" type="text" name="Name" class="form-control"
                                value="{{ $grade->getTranslation('Name', 'ar') }}" required>
                        </div>

                        <div class="col">
                            <label for="Name_en{{ $grade->id }}" class="mr-sm-2">
                                {{ trans('Grades_trans.stage_name_en') }}:
                            </label>
                            <input id="Name_en{{ $grade->id }}" type="text" class="form-control"
                                value="{{ $grade->getTranslation('Name', 'en') }}" name="Name_en" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="Notes{{ $grade->id }}">{{ trans('Grades_trans.Notes') }}:</label>
                        <textarea class="form-control" name="Notes" id="Notes{{ $grade->id }}"
                            rows="3">{{ $grade->Notes }}</textarea>
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