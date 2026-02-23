<div>
    {{-- Success/Error feedback --}}
    @if (!empty($successMessage))
        <div class="alert alert-success" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            {{ $successMessage }}
        </div>
    @endif

    @if ($catchError)
        <div class="alert alert-danger" id="success-danger">
            <button type="button" class="close" data-dismiss="alert">x</button>
            {{ $catchError }}
        </div>
    @endif

    {{-- Wizard progress header --}}
    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" type="button" class="btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-success' }}">1</a>
                <p>{{ trans('Parent_trans.Step1') }}</p>
            </div>

            <div class="stepwizard-step">
                <a href="#step-2" type="button" class="btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-success' }}">2</a>
                <p>{{ trans('Parent_trans.Step2') }}</p>
            </div>

            <div class="stepwizard-step">
                <a href="#step-3" type="button" class="btn btn-circle {{ $currentStep != 3 ? 'btn-default' : 'btn-success' }}" disabled>3</a>
                <p>{{ trans('Parent_trans.Step3') }}</p>
            </div>
        </div>
    </div>

    {{-- Step 1: Father data --}}
    @include('livewire.guardians.father-form')

    {{-- Step 2: Mother data --}}
    @include('livewire.guardians.mother-form')

    {{-- Step 3: Final confirmation --}}
    <div class="setup-content" id="step-3" @if ($currentStep != 3) style="display: none;" @endif>
        <h3 style="font-family: 'Cairo', sans-serif;">{{ trans('Parent_trans.delete_warning') }}</h3>
        <br>

        <button class="btn btn-danger btn-sm nextBtn btn-lg pull-right" type="button" wire:click="back(2)">
            {{ trans('Parent_trans.Back') }}
        </button>

        <button class="btn btn-success btn-sm btn-lg pull-right" type="button" wire:click="submitForm">
            {{ trans('Parent_trans.Finish') }}
        </button>
    </div>
</div>
