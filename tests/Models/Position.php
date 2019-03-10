<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\PositionVersioning;
use Cohrosonline\EloquentVersionable\Versionable;
use Cohrosonline\EloquentVersionable\VersionableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Position
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Position extends Model implements VersionableContract
{
    use Versionable, SoftDeletes;

    const VERSIONING_MODEL = PositionVersioning::class;

    const VERSIONED_TABLE = 'positions_versioning';

    const NEXT_COLUMN = "next";

    protected $guarded = [];

    protected $versioningEnabled = true;
}
