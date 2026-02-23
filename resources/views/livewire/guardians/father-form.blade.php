<div class="setup-content" id="step-1" @if ($currentStep != 1) style="display: none;" @endif>
    <br>

    {{-- Account fields --}}
    <div class="form-row">
        <div class="col">
            <label>{{ trans('Parent_trans.Email') }}</label>
            <input type="email" wire:model.live="email" class="form-control">
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Password') }}</label>
            <input type="password" wire:model="password" class="form-control">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Father names --}}
    <div class="form-row">
        <div class="col">
            <label>{{ trans('Parent_trans.Name_Father') }}</label>
            <input type="text" wire:model="father_name" class="form-control">
            @error('father_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Name_Father_en') }}</label>
            <input type="text" wire:model="father_name_en" class="form-control">
            @error('father_name_en')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Father identifiers and contact --}}
    <div class="form-row">
        <div class="col-md-3">
            <label>{{ trans('Parent_trans.Job_Father') }}</label>
            <input type="text" wire:model="father_job" class="form-control">
            @error('father_job')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label>{{ trans('Parent_trans.Job_Father_en') }}</label>
            <input type="text" wire:model="father_job_en" class="form-control">
            @error('father_job_en')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.National_ID_Father') }}</label>
            <input type="text" wire:model.live="father_national_id" class="form-control">
            @error('father_national_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Passport_ID_Father') }}</label>
            <input type="text" wire:model.live="father_passport_id" class="form-control">
            @error('father_passport_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Phone_Father') }}</label>
            <input type="text" wire:model.live="father_phone" class="form-control">
            @error('father_phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Father lookup selects --}}
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>{{ trans('Parent_trans.Nationality_Father_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="father_nationality_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($nationalities as $nationality)
                    <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                @endforeach
            </select>
            @error('father_nationality_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label>{{ trans('Parent_trans.Blood_Type_Father_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="father_blood_type_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($blood_types as $bloodType)
                    <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                @endforeach
            </select>
            @error('father_blood_type_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label>{{ trans('Parent_trans.Religion_Father_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="father_religion_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                @endforeach
            </select>
            @error('father_religion_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Father address --}}
    <div class="form-group">
        <label>{{ trans('Parent_trans.Address_Father') }}</label>
        <textarea class="form-control" wire:model="father_address" rows="4"></textarea>
        @error('father_address')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" wire:click="firstStepSubmit" type="button">
        {{ trans('Parent_trans.Next') }}
    </button>
</div>
