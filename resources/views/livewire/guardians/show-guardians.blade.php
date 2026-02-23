{{-- Guardians list actions --}}
<button class="btn btn-success btn-sm btn-lg pull-right" wire:click="showAddForm" type="button">
    {{ trans('Parent_trans.add_parent') }}
</button>

<br><br>

{{-- Guardians data table --}}
<div class="table-responsive">
    <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
        style="text-align: center">
        <thead>
            <tr class="table-success">
                <th>#</th>
                <th>{{ trans('Parent_trans.Email') }}</th>
                <th>{{ trans('Parent_trans.Name_Father') }}</th>
                <th>{{ trans('Parent_trans.National_ID_Father') }}</th>
                <th>{{ trans('Parent_trans.Passport_ID_Father') }}</th>
                <th>{{ trans('Parent_trans.Phone_Father') }}</th>
                <th>{{ trans('Parent_trans.Job_Father') }}</th>
                <th>{{ trans('Parent_trans.Processes') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($guardians as $guardian)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $guardian->email }}</td>
                    <td>{{ $guardian->father_name }}</td>
                    <td>{{ $guardian->father_national_id }}</td>
                    <td>{{ $guardian->father_passport_id }}</td>
                    <td>{{ $guardian->father_phone }}</td>
                    <td>{{ $guardian->father_job }}</td>
                    <td>
                        <button wire:click="edit({{ $guardian->id }})" class="btn btn-primary btn-sm"
                            title="{{ trans('Parent_trans.Edit') }}">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm" wire:click="delete({{ $guardian->id }})"
                            title="{{ trans('Parent_trans.Delete') }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
