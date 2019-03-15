<?php

namespace Kiqstyle\EloquentVersionable;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class VersionedModel extends Model implements VersionableContract
{
    use SoftDeletes, Versionable;

    const NEXT_COLUMN = "next";

    protected $versioningEnabled = true;
}
