<?php

namespace Cohrosonline\EloquentVersionable\Test\Models\Versioning;

use Cohrosonline\EloquentVersionable\Test\Models\Employee;

class EmployeeVersioning extends Employee
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'employees_versioning';
}
