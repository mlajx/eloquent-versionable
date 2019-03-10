<?php

namespace Cohrosonline\EloquentVersionable;

use Carbon\Carbon;

/**
 * @property string date
 */
class VersioningDate
{
    private $date;

    public function setDate($date = null)
    {
        if (is_null($date)) {
            $date = Carbon::now();
        }

        if (is_string($date)) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        }

        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function unsetDate()
    {
        $this->date = null;
    }

    public function issetDate()
    {
        return $this->date;
    }
}
