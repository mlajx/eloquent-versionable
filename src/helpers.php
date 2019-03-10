<?php

use Cohrosonline\EloquentVersionable\VersioningDate;

if (!function_exists('versioningDate')) {
    /**
     * @return VersioningDate
     */
    function versioningDate()
    {
        return app('versioningDate');
    }
}
