<?php

namespace App\Models\Concerns;

use RuntimeException;

trait PreventsForceDelete
{
    public static function bootPreventsForceDelete(): void
    {
        static::deleting(function ($model): void {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                throw new RuntimeException(trans('fees_trans.force_delete_not_allowed'));
            }
        });
    }
}
