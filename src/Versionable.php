<?php

namespace Cohrosonline\EloquentVersionable;

use Cohrosonline\EloquentVersionable\Test\Models\Versioning\DummyVersioning;
use Illuminate\Support\Str;

trait Versionable
{
    public static function bootVersionable()
    {
        static::addGlobalScope(new VersionableScope());

        static::saved(function ($model) {
            if ($model->isVersioningEnabled()) {
                VersioningPersistence::createVersionedRecord($model);
            }
        });

        static::updated(function ($model) {
            if ($model->isVersioningEnabled()) {
                VersioningPersistence::updateNextColumnOfLastVersionedRegister($model);
            }
        });

        static::deleted(function ($model) {
            if ($model->isVersioningEnabled()) {
                VersioningPersistence::updateNextColumnOfLastVersionedRegister($model);
                VersioningPersistence::createDeletedVersionedRecord($model);
            }
        });
    }

    public function insert($data)
    {
        foreach ($data as $register) {
            $this->create($register);
        }
    }

    public function getTable()
    {
        // My eyes, it burns T_T
        // When save or create (create calls save), get original table name, when using find get versioned table
        $calledBy = debug_backtrace()[3]['function'];

        $methods = [
            'save',
            'runSoftDelete',
            'delete',
            'performDeleteOnModel',
            'create',
            'updateOrCreate',
            'addUpdatedAtColumn'
        ];

        if (versioningDate()->issetDate() && ($this->isVersioningEnabled() && !in_array($calledBy, $methods))) {
            return $this->getVersioningTable();
        }

        return $this->getOriginalTable();
    }

    public function getOriginalTable()
    {
        return isset($this->table)
            ? $this->table
            : Str::snake(Str::pluralStudly(class_basename($this)));
    }

    /**
     * @return boolean
     */
    public function isVersioningEnabled()
    {
        return $this->versioningEnabled;
    }

    /**
     * @param boolean $versioningEnabled
     */
    public function setVersioningEnabled($versioningEnabled)
    {
        $this->versioningEnabled = $versioningEnabled;
    }

    public function unsetVersioning()
    {
        $this->versioningEnabled = false;
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getVersioningModel()
    {
        return ($this::VERSIONING_MODEL !== null) ? $this::VERSIONING_MODEL : get_class($this) . 'Versioning';
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getVersioningTable()
    {
        return ($this::VERSIONED_TABLE !== null) ? $this::VERSIONED_TABLE : $this->table . '_versioning';
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getNextColumn()
    {
        return ($this::NEXT_COLUMN !== null) ? $this::NEXT_COLUMN : 'next';
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    public function getQualifiedNxtColumn()
    {
        return $this->getVersioningTable() . '.' . $this->getNextColumn();
    }

    /**
     * Get the query builder without the scope applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function now()
    {
        return with(new static)->newQueryWithoutScope(new VersionableScope());
    }

    public function getQualifiedVersioningKeyName()
    {
        return $this->getVersioningTable() . '.' . $this->getKeyName();
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        $model = new static((array) $attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        $model->setTable($this->getOriginalTable());

        return $model;
    }
}
