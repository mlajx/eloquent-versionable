<?php

namespace Kiqstyle\EloquentVersionable\Test\Models\Versioning;

use Kiqstyle\EloquentVersionable\Test\Models\Position;

class PositionVersioning extends Position
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'positions_versioning';
}
