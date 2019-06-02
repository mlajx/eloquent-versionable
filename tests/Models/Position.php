<?php

namespace Kiqstyle\EloquentVersionable\Test\Models;

use Kiqstyle\EloquentVersionable\Test\Models\Versioning\PositionVersioning;
use Kiqstyle\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package Kiqstyle\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Position extends VersionedModel
{
    public function competencies()
    {
        $pivot = new PositionCompetency;

        return $this->belongsToMany(Competency::class, $pivot->getTable());
    }
}
