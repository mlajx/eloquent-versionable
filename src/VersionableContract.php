<?php

namespace Cohrosonline\EloquentVersionable;

interface VersionableContract
{
    public static function bootVersionable();

    public function isVersioningEnabled();

    public function setVersioningEnabled($versioningEnabled);

    public function getVersioningModel();

    public function getVersioningTable();

    public function getNxtColumn();

    public function now();
}
