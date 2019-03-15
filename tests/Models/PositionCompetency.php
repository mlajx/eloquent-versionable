<?php

namespace Kiqstyle\EloquentVersionable\Test\Models;

use Kiqstyle\EloquentVersionable\Test\Models\Versioning\PositionCompetencyVersioning;
use Kiqstyle\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PositionCompetency
 * @package Kiqstyle\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class PositionCompetency extends VersionedModel
{
    const VERSIONING_MODEL = PositionCompetencyVersioning::class;

    const VERSIONED_TABLE = 'position_competency_versioning';

    protected $table = 'position_competency';

    protected $guarded = [];
}
