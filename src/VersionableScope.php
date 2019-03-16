<?php

namespace Kiqstyle\EloquentVersionable;

use Illuminate\Database\Eloquent\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VersionableScope implements Scope
{
    /**
     * Apply scope on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (versioningDate()->issetDate() && ($model->isVersioningEnabled() === true)) {
            $datetime = versioningDate()->getDate()->format('Y-m-d H:i:s');

            $builder->where($model->getVersioningTable() . '.' . $model->getUpdatedAtColumn(), '<=', $datetime)
                ->whereNull($model->getVersioningTable() . '.' . $model->getDeletedAtColumn())
                ->where(function (Builder $q) use ($datetime, $model) {
                    $q->where($model->getQualifiedNxtColumn(), '>', $datetime);
                    $q->orWhereNull($model->getQualifiedNxtColumn());
                });

            $joins = $builder->getQuery()->joins ?? [];
            if (count($joins) > 0) {
                foreach ($joins as $join) {
                    if (strpos($join->table, '_versioning') !== false) {
                        $builder->where($join->table . '.' . $model->getUpdatedAtColumn(), '<=', $datetime)
                            ->whereNull($join->table . '.' . $model->getDeletedAtColumn())
                            ->where(function (Builder $q) use ($datetime, $join, $model) {
                                $q->where($join->table . '.' . $model->getNextColumn(), '>', $datetime);
                                $q->orWhereNull($join->table . '.' . $model->getNextColumn());
                            });
                    }
                }
            }
        }
    }
}
