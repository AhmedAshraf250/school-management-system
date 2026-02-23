<div class="setup-content" id="step-2" @if ($currentStep != 2) style="display: none;" @endif>
    <br>

    {{-- Mother names --}}
    <div class="form-row">
        <div class="col">
            <label>{{ trans('Parent_trans.Name_Mother') }}</label>
            <input type="text" wire:model="mother_name" class="form-control">
            @error('mother_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Name_Mother_en') }}</label>
            <input type="text" wire:model="mother_name_en" class="form-control">
            @error('mother_name_en')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Mother identifiers and contact --}}
    <div class="form-row">
        <div class="col-md-3">
            <label>{{ trans('Parent_trans.Job_Mother') }}</label>
            <input type="text" wire:model="mother_job" class="form-control">
            @error('mother_job')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label>{{ trans('Parent_trans.Job_Mother_en') }}</label>
            <input type="text" wire:model="mother_job_en" class="form-control">
            @error('mother_job_en')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.National_ID_Mother') }}</label>
            <input type="text" wire:model.live="mother_national_id" class="form-control">
            @error('mother_national_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Passport_ID_Mother') }}</label>
            <input type="text" wire:model.live="mother_passport_id" class="form-control">
            @error('mother_passport_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col">
            <label>{{ trans('Parent_trans.Phone_Mother') }}</label>
            <input type="text" wire:model.live="mother_phone" class="form-control">
            @error('mother_phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Mother lookup selects --}}
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>{{ trans('Parent_trans.Nationality_Mother_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="mother_nationality_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($nationalities as $nationality)
                    <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                @endforeach
            </select>
            @error('mother_nationality_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label>{{ trans('Parent_trans.Blood_Type_Mother_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="mother_blood_type_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($blood_types as $bloodType)
                    <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                @endforeach
            </select>
            @error('mother_blood_type_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label>{{ trans('Parent_trans.Religion_Mother_id') }}</label>
            <select class="custom-select my-1 mr-sm-2" wire:model="mother_religion_id">
                <option value="">{{ trans('Parent_trans.Choose') }}...</option>
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                @endforeach
            </select>
            @error('mother_religion_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Mother address --}}
    <div class="form-group">
        <label>{{ trans('Parent_trans.Address_Mother') }}</label>
        <textarea class="form-control" wire:model="mother_address" rows="4"></textarea>
        @error('mother_address')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-danger btn-sm nextBtn btn-lg pull-right" type="button" wire:click="back(1)">
        {{ trans('Parent_trans.Back') }}
    </button>

    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="button" wire:click="secondStepSubmit">
        {{ trans('Parent_trans.Next') }}
    </button>
</div>
