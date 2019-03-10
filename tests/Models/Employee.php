<?php

namespace Cohrosonline\EloquentVersionable\Test\Models;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\EmployeeVersioning;
use Cohrosonline\EloquentVersionable\Versionable;
use Cohrosonline\EloquentVersionable\VersionableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee
 * @package Cohrosonline\EloquentVersionable\Test\Models
 * @mixin Model
 * @mixin Builder
 */
class Employee extends Model implements VersionableContract
{
    use Versionable, SoftDeletes;

    const VERSIONING_MODEL = EmployeeVersioning::class;

    const VERSIONED_TABLE = 'employees_versioning';

    const NEXT_COLUMN = "next";

    protected $guarded = [];

    protected $versioningEnabled = true;

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
