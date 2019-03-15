<?php

namespace Kiqstyle\EloquentVersionable\Test\Models;

use Kiqstyle\EloquentVersionable\Test\Models\Versioning\CompetencyVersioning;
use Kiqstyle\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Competency
 * @package Kiqstyle\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Competency extends VersionedModel
{
    const VERSIONING_MODEL = CompetencyVersioning::class;

    const VERSIONED_TABLE = 'competencies_versioning';

    protected $guarded = [];

    public function positions()
    {
        return $this->belongsToMany(Position::class)
            ->using(PositionCompetency::class);
    }
}
