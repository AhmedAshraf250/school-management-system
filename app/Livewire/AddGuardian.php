<?php

namespace App\Livewire;

use App\Livewire\Concerns\ManagesGuardianFatherData;
use App\Livewire\Concerns\ManagesGuardianMotherData;
use App\Models\BloodType;
use App\Models\Guardian;
use App\Models\GuardianAttachment;
use App\Models\Nationality;
use App\Models\Religion;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddGuardian extends Component
{
    use ManagesGuardianFatherData;
    use ManagesGuardianMotherData;
    use WithFileUploads;

    public int $currentStep = 1;

    public string $successMessage = '';

    public bool $updateMode = false;

    public ?string $catchError = null;

    public bool $show_table = true;

    public ?int $guardian_id = null;

    public array $photos = [];

    public function render(): View
    {
        return view('livewire.add-guardian', [
            'nationalities' => Nationality::query()->get(),
            'blood_types' => BloodType::query()->get(),
            'religions' => Religion::query()->get(),
            'guardians' => Guardian::query()->latest()->get(),
        ]);
    }

    public function showAddForm(): void
    {
        $this->resetFormState();
        $this->show_table = false;
    }

    public function firstStepSubmit(): void
    {
        $this->validate($this->fatherStepRules($this->guardian_id, $this->updateMode));
        $this->currentStep = 2;
    }

    public function secondStepSubmit(): void
    {
        $this->validate($this->motherStepRules($this->guardian_id));
        $this->currentStep = 3;
    }

    public function back(int $step): void
    {
        if ($step < 1 || $step > 3) {
            return;
        }

        $this->currentStep = $step;
    }

    public function updated(string $propertyName): void
    {
        $rules = array_merge(
            $this->fatherLiveValidationRules(),
            $this->motherLiveValidationRules(),
        );

        if (! array_key_exists($propertyName, $rules)) {
            return;
        }

        $this->validateOnly($propertyName, $rules);
    }

    public function submitForm(): void
    {
        $this->catchError = null;
        $this->validateBeforePersist();

        try {
            $this->persistGuardian();
            $this->successMessage = trans('messages.success');
            $this->resetFormState();
        } catch (\Throwable $exception) {
            Log::error('Failed to create guardian.', ['exception' => $exception]);
            $this->catchError = trans('messages.error');
        }
    }

    public function submitFormEdit(): void
    {
        if (! $this->guardian_id) {
            return;
        }

        $this->catchError = null;
        $this->validateBeforePersist($this->guardian_id, true);

        try {
            $guardian = Guardian::query()->findOrFail($this->guardian_id);
            $this->persistGuardian($guardian);
            $this->successMessage = trans('messages.Update');
            $this->resetFormState();
        } catch (\Throwable $exception) {
            Log::error('Failed to update guardian.', ['exception' => $exception]);
            $this->catchError = trans('messages.error');
        }
    }

    public function edit(int $id): void
    {
        $guardian = Guardian::query()->findOrFail($id);

        $this->resetFormState();
        $this->show_table = false;
        $this->updateMode = true;
        $this->guardian_id = $guardian->id;

        $this->fillFatherDataFromGuardian($guardian);
        $this->fillMotherDataFromGuardian($guardian);
    }

    public function delete(int $id): void
    {
        try {
            $this->catchError = null;

            DB::transaction(function () use ($id): void {
                $guardian = Guardian::query()->findOrFail($id);

                Storage::disk('public')->deleteDirectory('attachments/guardians/'.$guardian->id);
                GuardianAttachment::query()->where('guardian_id', $guardian->id)->delete();
                $guardian->delete();
            });

            $this->successMessage = trans('messages.Delete');
            $this->show_table = true;
        } catch (\Throwable $exception) {
            Log::error('Failed to delete guardian.', ['exception' => $exception]);
            $this->catchError = trans('messages.error');
        }
    }

    protected function persistGuardian(?Guardian $guardian = null): void
    {
        DB::transaction(function () use ($guardian): void {
            $model = $guardian ?? new Guardian;

            $this->fillFatherDataIntoGuardian($model);
            $this->fillMotherDataIntoGuardian($model);
            $model->save();

            $this->storeGuardianAttachments($model);
        });
    }

    protected function storeGuardianAttachments(Guardian $guardian): void
    {
        if (empty($this->photos)) {
            return;
        }

        foreach ($this->photos as $photo) {
            $fileName = Str::uuid()->toString().'_'.$photo->getClientOriginalName();
            $photo->storeAs('attachments/guardians/'.$guardian->id, $fileName, 'public');

            GuardianAttachment::query()->create([
                'file_name' => $fileName,
                'guardian_id' => $guardian->id,
            ]);
        }
    }

    protected function resetFormState(): void
    {
        $this->clearForm();
        $this->currentStep = 1;
        $this->updateMode = false;
        $this->guardian_id = null;
        $this->catchError = null;
    }

    public function clearForm(): void
    {
        $this->resetValidation();

        $this->photos = [];
        $this->successMessage = '';
        $this->resetFatherData();
        $this->resetMotherData();
    }

    protected function validateBeforePersist(?int $guardianId = null, bool $isUpdate = false): void
    {
        $rules = array_merge(
            $this->fatherStepRules($guardianId, $isUpdate),
            $this->motherStepRules($guardianId),
            ['photos.*' => ['nullable', 'image', 'max:2048']],
        );

        $this->validate($rules);
    }
}
