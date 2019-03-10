<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Cohrosonline\EloquentVersionable\Test\Models\Dummy;

class VersionableQueryTest extends TestCase
{

    /** @test */
    public function it_finds_last_versioned_register()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->update(['name' => 'updated again']);

        $dummyModel = new Dummy;
        $dummyModel->unsetVersioning();
        $dummy = $dummyModel->first();
        $versionedDummy = Dummy::find($dummy->id);

        $this->assertVersioning($dummy, $versionedDummy);
    }

    /** @test */
    public function it_works_with_soft_delete()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->delete();

        $dummyModel = new Dummy;
        $dummyModel->unsetVersioning();
        $dummy = $dummyModel->withTrashed()->first();
        $versioned = $this->getVersioned($dummy->id);

        $this->assertVersioning($dummy, $versioned->get(2));

        $this->assertNotNull($versioned->get(2)->_id);
        $this->assertNull($versioned->get(2)->next);
    }

    /** @test */
    public function it_finds_old_registers_based_on_versioning_date()
    {
        $dummy = Dummy::first();
        $this->update($dummy, ['name' => 'updated']);
        $this->update($dummy, ['name' => 'updated again']);

        $now = $this->setFakeNow('2019-01-01 12:00:01');
        versioningDate()->setDate($now);

        $dummy = Dummy::find($dummy->id);

        $this->assertEquals($dummy->id, 1);
        $this->assertEquals($dummy->name, 'updated');
        $this->assertNotNull($dummy->next);
    }

    /** @test */
    public function it_works_with_update_or_create()
    {
        $this->setFakeNow('2019-01-01 12:00:01');
        $dummy = Dummy::updateOrCreate(['id' => '999'], ['name' => 'new dummy']);
        $versioned = $this->getVersioned($dummy->id);

        $this->assertVersioning($dummy, $versioned->get(0));

        $dummy = Dummy::updateOrCreate(['id' => '999'], ['name' => 'updated dummy']);
        $versioned = $this->getVersioned($dummy->id);

        $this->assertVersioning($dummy, $versioned->get(1));
    }

    // @todo method first does not return id 1 after it was updated and set deleted_at in versioning
}
