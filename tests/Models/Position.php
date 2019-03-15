<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\PositionVersioning;
use Cohrosonline\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Position
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Position extends VersionedModel
{
    const VERSIONING_MODEL = PositionVersioning::class;

    const VERSIONED_TABLE = 'positions_versioning';

    protected $guarded = [];

    public function competencies()
    {
        $pivot = new PositionCompetency;

        return $this->belongsToMany(Competency::class, $pivot->getTable());
    }
}
