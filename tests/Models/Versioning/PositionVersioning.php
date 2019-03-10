<?php

namespace Cohrosonline\EloquentVersionable\Test\Models\Versioning;

use Cohrosonline\EloquentVersionable\Test\Models\Employee;

class PositionVersioning extends Employee
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'positions_versioning';
}
