<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService {

    public static function deleteOldImage(Model $model, string $imageAttribute, string $disk)
    {
        if ($model->isDirty($imageAttribute) && ($model->getOriginal($imageAttribute) !== null)) {

            Storage::disk($disk)->delete($model->getOriginal($imageAttribute));

        }
    }

}
