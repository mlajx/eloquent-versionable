<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Cohrosonline\EloquentVersionable\Test\Models\Employee;
use Cohrosonline\EloquentVersionable\Test\Models\Position;

class VersionableQueryTest extends TestCase
{

    /** @test */
    public function it_finds_last_versioned_register()
    {
        $employee = Employee::first();
        $this->update($employee, ['name' => 'updated']);
        $this->update($employee, ['name' => 'updated 2']);

        $employeeModel = new Employee;
        $employeeModel->unsetVersioning();
        $employee = $employeeModel->first();
        $versionedDummy = Employee::find($employee->id);

        $this->assertOriginalEqualsVersioning($employee, $versionedDummy);
    }

    /** @test */
    public function it_works_with_soft_delete()
    {
        $employee = Employee::first();
        $this->update($employee, ['name' => 'updated']);
        $employee->delete();

        $employeeModel = new Employee;
        $employeeModel->unsetVersioning();
        $employee = $employeeModel->withTrashed()->first();
        $versioned = $this->getVersioned($employee);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(2));
    }

    /** @test */
    public function it_finds_old_registers_based_on_versioning_date()
    {
        $employee = Employee::first();
        $this->update($employee, ['name' => 'updated']);
        $this->update($employee, ['name' => 'updated 2']);

        $now = $this->setFakeNow('2019-01-01 12:00:01');
        versioningDate()->setDate($now);

        $employee = Employee::find($employee->id);

        $this->assertEquals($employee->id, 1);
        $this->assertEquals($employee->name, 'updated');
        $this->assertNotNull($employee->next);
    }

    /** @test */
    public function it_works_with_update_or_create()
    {
        $this->setFakeNow('2019-01-01 12:00:01');
        $employee = Employee::updateOrCreate(['id' => '999'], ['name' => 'new employee']);
        $versioned = $this->getVersioned($employee);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(0));

        $employee = Employee::updateOrCreate(['id' => '999'], ['name' => 'updated employee']);
        $versioned = $this->getVersioned($employee);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(1));
    }

    /** @test */
    public function it_finds_versioned_results_with_has_one()
    {
        $employee = Employee::with('position')->first();
        $this->update($employee->position, ['name' => 'updated']);
        $this->update($employee->position, ['name' => 'updated 2']);

        $versioned = $this->getVersioned($employee->position);

        $this->assertOriginalEqualsVersioning($employee->position, $versioned->get(2));
    }

    /** @test */
    public function it_works_with_soft_delete_in_has_one()
    {
        $employee = Employee::with('position')->first();
        $this->update($employee->position, ['name' => 'updated']);
        $employee->position->delete();

        $positionModel = new Position;
        $positionModel->unsetVersioning();
        $position = $positionModel->withTrashed()->first();
        $versioned = $this->getVersioned($position);

        $this->assertOriginalEqualsVersioning($position, $versioned->get(2));
    }

    /** @test */
    public function it_finds_versioned_results_with_has_one_based_on_versioning_date()
    {
        $employee = Employee::with('position')->first();
        $this->update($employee->position, ['name' => 'updated']);
        $this->update($employee->position, ['name' => 'updated 2']);

        $now = $this->setFakeNow('2019-01-01 12:00:01');
        versioningDate()->setDate($now);

        $employee = Employee::with('position')->first();

        $this->assertEquals($employee->position->id, 1);
        $this->assertEquals($employee->position->name, 'updated');
        $this->assertNotNull($employee->position->next);
    }
}
