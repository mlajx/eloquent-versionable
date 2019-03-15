<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\PositionCompetencyVersioning;
use Cohrosonline\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PositionCompetency
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class PositionCompetency extends VersionedModel
{
    const VERSIONING_MODEL = PositionCompetencyVersioning::class;

    const VERSIONED_TABLE = 'position_competency_versioning';

    protected $table = 'position_competency';
}
