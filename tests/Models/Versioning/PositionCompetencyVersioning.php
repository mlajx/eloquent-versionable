<?php

namespace Cohrosonline\EloquentVersionable\Test\Models\Versioning;

use Cohrosonline\EloquentVersionable\Test\Models\PositionCompetency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PositionCompetency
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class PositionCompetencyVersioning extends PositionCompetency
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'position_competency_versioning';
}
