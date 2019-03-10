<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Cohrosonline\EloquentVersionable\Test\Models\Employee;

class VersionablePersistenceTest extends TestCase
{

    /** @test */
    public function it_creates_versioning_register_on_model_create()
    {
        $employee = Employee::first();
        $versioned = $this->getVersioned($employee);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(0));
    }

    /** @test */
    public function it_creates_versioning_register_on_model_update()
    {
        $employee = Employee::first();
        $this->update($employee, ['name' => 'updated']);
        $this->update($employee, ['name' => 'updated 2']);

        $versioned = $this->getVersioned($employee);

        $this->assertEquals($versioned->get(0)->name, '1');
        $this->assertEquals($versioned->get(0)->next, $versioned->get(1)->updated_at);

        $this->assertEquals($versioned->get(1)->name, 'updated');
        $this->assertEquals($versioned->get(1)->next, $versioned->get(2)->updated_at);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(2));
        $this->assertNull($versioned->get(2)->deleted_at);
    }

    /** @test */
    public function it_works_with_soft_delete()
    {
        $employee = Employee::first();
        $this->update($employee, ['name' => 'updated']);
        $employee->delete();

        $versioned = $this->getVersioned($employee);

        $this->assertEquals($versioned->get(0)->name, '1');
        $this->assertEquals($versioned->get(0)->next, $versioned->get(1)->updated_at);

        $this->assertEquals($versioned->get(1)->name, 'updated');
        $this->assertEquals($versioned->get(1)->next, $versioned->get(2)->updated_at);

        $this->assertOriginalEqualsVersioning($employee, $versioned->get(2));
        $this->assertNotNull($versioned->get(2)->deleted_at);
    }
}
