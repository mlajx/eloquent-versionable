<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Carbon\Carbon;
use Cohrosonline\EloquentVersionable\Test\Models\Employee;
use Cohrosonline\EloquentVersionable\Test\Models\DummyBelongsTo;
use Cohrosonline\EloquentVersionable\Test\Models\Position;
use Cohrosonline\EloquentVersionable\Test\Models\Versioning\EmployeeVersioning;
use Cohrosonline\EloquentVersionable\VersioningServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setFakeNow('2019-01-01 12:00:00');
        $this->setUpDatabase();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            VersioningServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('position_id');
            $table->string('name');

            $table->foreign('position_id')->on('id')->references('positions');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('employees_versioning', function (Blueprint $table) {
            $table->increments('_id');
            $table->unsignedInteger('id');
            $table->unsignedInteger('position_id');
            $table->string('name');

            $table->timestamps();
            $table->dateTime('next')->nullable();
            $table->softDeletes();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('positions_versioning', function (Blueprint $table) {
            $table->increments('_id');
            $table->unsignedInteger('id');
            $table->string('name');

            $table->timestamps();
            $table->dateTime('next')->nullable();
            $table->softDeletes();
        });

        collect(range(1, 3))->each(function (int $i) {
            Position::create(['name' => $i]);
        });

        $position = Position::first();
        collect(range(1, 3))->each(function (int $i) use ($position) {
            Employee::create(['name' => $i, 'position_id' => $position->id]);
        });
    }

    protected function update($entity, $attributes)
    {
        Carbon::setTestNow(Carbon::now()->addSecond());
        $entity->update($attributes);
    }

    protected function setFakeNow($time = '2019-01-01 12:00:00')
    {
        $time = Carbon::createFromFormat('Y-m-d H:i:s', $time);
        return Carbon::setTestNow($time);
    }

    protected function assertOriginalEqualsVersioning($original, $versioned)
    {
        $this->assertEquals($original->id, $versioned->id);
        $this->assertEquals($original->name, $versioned->name);
        $this->assertEquals($original->created_at, $versioned->created_at);
        $this->assertEquals($original->updated_at, $versioned->updated_at);
        $this->assertEquals($original->deleted_at, $versioned->deleted_at);

        $this->assertNotNull($versioned->_id);
        $this->assertNull($versioned->next);
    }

    /**
     * @param $entity
     * @return EmployeeVersioning|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    protected function getVersioned($entity)
    {
        return $entity->withoutGlobalScopes()->where('id', $entity->id)->get();
    }
}
