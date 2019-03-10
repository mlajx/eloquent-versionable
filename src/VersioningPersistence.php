<?php

namespace Cohrosonline\EloquentVersionable;

use Illuminate\Database\Eloquent\Model;

class VersioningPersistence
{
    public static function createVersionedRecord(Model $model)
    {
        self::getVersionedModel($model)->create($model->getAttributes());
    }

    public static function updateNextColumnOfLastVersionedRegister(Model $model)
    {
        $lastVersioned = self::getVersionedModel($model)->where('id', $model->id)
            ->orderBy('_id', 'desc')
            ->first();
        $lastVersioned->timestamps = false;

        $lastVersioned->update(['next' => $model->updated_at]);
    }

    public static function createDeletedVersionedRecord(Model $model)
    {
        $versionedInstance = self::getVersionedModel($model);
        $versionedInstance->fill($model->getAttributes());
        $versionedInstance->updated_at = $model->updated_at;
        $versionedInstance->deleted_at = $model->updated_at;
        $versionedInstance->save();
    }

    private static function getVersionedModel(Model $model)
    {
        $versionedClassName = $model->getVersioningModel();
        return new $versionedClassName;
    }
}
