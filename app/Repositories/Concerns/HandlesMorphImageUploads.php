<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesMorphImageUploads
{
    /**
     * @param  array<int, UploadedFile>  $photos
     */
    protected function storeMorphImages(Model $model, array $photos, string $directory, string $disk = 'public'): void
    {
        if ($photos === []) {
            return;
        }

        foreach ($photos as $photo) {
            $fileName = Str::uuid()->toString().'_'.$photo->getClientOriginalName();
            $storedPath = $photo->storeAs($directory.'/'.$model->getKey(), $fileName, $disk);

            if ($storedPath === false) {
                continue;
            }

            $model->images()->create([
                'file_name' => $fileName,
                'path' => $storedPath,
            ]);
        }
    }

    protected function deleteMorphImages(Model $model, string $disk = 'public'): void
    {
        $images = $model->images()->get();

        foreach ($images as $image) {
            Storage::disk($disk)->delete($image->path);
        }

        $model->images()->delete();
    }
}
