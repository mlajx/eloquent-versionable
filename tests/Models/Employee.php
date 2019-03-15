<?php

namespace Kiqstyle\EloquentVersionable\Test\Models;

use Kiqstyle\EloquentVersionable\Test\Models\Versioning\EmployeeVersioning;
use Kiqstyle\EloquentVersionable\Versionable;
use Kiqstyle\EloquentVersionable\VersionableContract;
use Kiqstyle\EloquentVersionable\VersionedModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee
 * @package Kiqstyle\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Employee extends VersionedModel
{
    const VERSIONING_MODEL = EmployeeVersioning::class;

    const VERSIONED_TABLE = 'employees_versioning';

    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
