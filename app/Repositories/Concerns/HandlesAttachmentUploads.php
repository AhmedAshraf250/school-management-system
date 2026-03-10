<?php

namespace App\Repositories\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

trait HandlesAttachmentUploads
{
    protected function storeAttachment(UploadedFile $file, string $directory, string $disk = 'public')
    {
        $fileName = Str::uuid()->toString() . '_' . $file->getClientOriginalName();
        $storedPath = $file->storeAs($directory, $fileName, $disk);

        if ($storedPath === false) {
            throw new RuntimeException('Failed to store attachment file.');
        }

        return [
            'file_name' => $fileName,
            'path' => $storedPath,
        ];
    }

    protected function deleteAttachment(?string $path, string $disk = 'public')
    {
        if (empty($path)) {
            return;
        }

        Storage::disk($disk)->delete($path);
    }

    protected function moveAttachment(string $from, string $to, string $disk = 'public')
    {
        $moved = Storage::disk($disk)->move($from, $to);

        if ($moved === false) {
            throw new RuntimeException('Failed to move attachment file.');
        }
    }
}
