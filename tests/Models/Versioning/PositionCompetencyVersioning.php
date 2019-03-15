<?php

namespace Kiqstyle\EloquentVersionable\Test\Models\Versioning;

use Kiqstyle\EloquentVersionable\Test\Models\PositionCompetency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PositionCompetency
 * @package Kiqstyle\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class PositionCompetencyVersioning extends PositionCompetency
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'position_competency_versioning';
}
