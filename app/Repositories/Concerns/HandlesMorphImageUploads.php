<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HandlesMorphImageUploads
{
    use HandlesAttachmentUploads;

    protected function storeMorphImages(Model $model, array $photos, string $directory, string $disk = 'public'): void
    {
        if ($photos === []) {
            return;
        }

        foreach ($photos as $photo) {
            $fileData = $this->storeAttachment($photo, $directory . '/' . $model->getKey(), $disk);

            $model->images()->create([
                'file_name' => $fileData['file_name'],
                'path' => $fileData['path'],
            ]);
        }
    }

    protected function deleteMorphImages(Model $model, string $disk = 'public'): void
    {
        $images = $model->images()->get();

        foreach ($images as $image) {
            $this->deleteAttachment($image->path, $disk);
        }

        $model->images()->delete();
    }
}
