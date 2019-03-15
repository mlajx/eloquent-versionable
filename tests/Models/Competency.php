<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\CompetencyVersioning;
use Cohrosonline\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Competency
 * @package Cohrosonline\EloquentVersionable\Test\Models
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
