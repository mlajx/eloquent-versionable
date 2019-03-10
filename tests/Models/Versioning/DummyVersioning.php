<?php

namespace Cohrosonline\EloquentVersionable\Test\Models\Versioning;

use Cohrosonline\EloquentVersionable\Test\Models\Dummy;

class DummyVersioning extends Dummy
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'dummies_versioning';
}
