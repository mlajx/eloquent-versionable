<?php

namespace Kiqstyle\EloquentVersionable\Test\Models\Versioning;

use Kiqstyle\EloquentVersionable\Test\Models\Employee;

class CompetencyVersioning extends Employee
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'competencies_versioning';
}
