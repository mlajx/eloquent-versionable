<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\DummyVersioning;
use Cohrosonline\EloquentVersionable\Versionable;
use Cohrosonline\EloquentVersionable\VersionableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Dummy
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Dummy extends Model implements VersionableContract
{
    use Versionable, SoftDeletes;

    const VERSIONING_MODEL = DummyVersioning::class;

    const VERSIONED_TABLE = 'dummies_versioning';

    const NEXT_COLUMN = "next";

    protected $table = 'dummies';

    protected $guarded = [];

    protected $versioningEnabled = true;
}
