<?php

namespace Kiqstyle\EloquentVersionable\Test\Models\Versioning;

use Kiqstyle\EloquentVersionable\Test\Models\Employee;

class EmployeeVersioning extends Employee
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'employees_versioning';
}
