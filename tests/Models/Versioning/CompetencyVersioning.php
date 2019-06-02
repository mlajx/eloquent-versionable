<?php

namespace Kiqstyle\EloquentVersionable\Test\Models\Versioning;

use Kiqstyle\EloquentVersionable\Test\Models\Competency;

class CompetencyVersioning extends Competency
{
    protected $versioningEnabled = false;

    protected $primaryKey = "_id";

    protected $table = 'competencies_versioning';
}
