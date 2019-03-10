<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Cohrosonline\EloquentVersionable\Test\Models\Dummy;

class VersionablePersistenceTest extends TestCase
{

    /** @test */
    public function it_creates_versioning_register_on_model_create()
    {
        $dummy = Dummy::first();
        $versioned = $this->getVersioned($dummy->id);

        $this->assertOriginalEqualsVersioning($dummy, $versioned->get(0));
    }

    /** @test */
    public function it_creates_versioning_register_on_model_update()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->update(['name' => 'updated again']);

        $versioned = $this->getVersioned($dummy->id);

        $this->assertEquals($versioned->get(0)->name, '1');
        $this->assertEquals($versioned->get(0)->next, $versioned->get(1)->updated_at);

        $this->assertEquals($versioned->get(1)->name, 'updated');
        $this->assertEquals($versioned->get(1)->next, $versioned->get(2)->updated_at);

        $this->assertOriginalEqualsVersioning($dummy, $versioned->get(2));
        $this->assertNull($versioned->get(2)->deleted_at);
    }

    /** @test */
    public function it_works_with_soft_delete()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->delete();

        $versioned = $this->getVersioned($dummy->id);

        $this->assertEquals($versioned->get(0)->name, '1');
        $this->assertEquals($versioned->get(0)->next, $versioned->get(1)->updated_at);

        $this->assertEquals($versioned->get(1)->name, 'updated');
        $this->assertEquals($versioned->get(1)->next, $versioned->get(2)->updated_at);

        $this->assertOriginalEqualsVersioning($dummy, $versioned->get(2));
        $this->assertNotNull($versioned->get(2)->deleted_at);
    }
}
