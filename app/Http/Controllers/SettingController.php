<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Models\Setting;
use App\Repositories\Concerns\HandlesAttachmentUploads;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    use HandlesAttachmentUploads;

    public function index()
    {
        $setting = array_merge(
            [
                'school_name' => '',
                'school_title' => '',
                'phone' => '',
                'school_email' => '',
                'address' => '',
                'end_first_term' => '',
                'end_second_term' => '',
                'logo' => '',
            ],
            Setting::query()->pluck('value', 'key')->toArray(),
        );

        $logoValue = $setting['logo'] ?? '';
        $logoUrl = $logoValue !== '' ? asset('storage/'.$logoValue) : null;

        // Technical route parameter for resource update; not part of settings domain logic.
        $settingId = (int) Setting::query()->value('id');

        return view('pages.settings.index', compact('setting', 'settingId', 'logoUrl'));
    }

    public function update(UpdateSettingRequest $request, Setting $_setting): RedirectResponse
    {
        $newLogoPath = null;
        $oldLogoPath = null;

        try {
            $validated = $request->validated();

            $payload = [
                'school_name' => $validated['school_name'],
                'school_title' => $validated['school_title'] ?? '',
                'phone' => $validated['phone'] ?? '',
                'school_email' => $validated['school_email'] ?? '',
                'address' => $validated['address'],
                'end_first_term' => $validated['end_first_term'] ?? '',
                'end_second_term' => $validated['end_second_term'] ?? '',
            ];

            if ($request->hasFile('logo')) {
                $fileData = $this->storeAttachment($request->file('logo'), 'attachments/settings/logo');
                $payload['logo'] = $fileData['path'];
                $newLogoPath = $fileData['path'];

                $oldLogoPath = (string) (Setting::query()->where('key', 'logo')->value('value') ?? '');
            }

            foreach ($payload as $key => $value) {
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => (string) $value],
                );
            }

            if ($newLogoPath !== null && $oldLogoPath !== null) {
                $this->deleteAttachment($oldLogoPath);
            }

            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('settings.index');
        } catch (\Throwable $exception) {
            if ($newLogoPath !== null) {
                $this->deleteAttachment($newLogoPath);
            }

            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }
}
