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
        $versionedDummy = Dummy::withoutGlobalScopes()->skip(2)->find($dummy->id);

        $this->assertVersioning($dummy, $versionedDummy);

        $this->assertNotNull($versionedDummy->_id);
        $this->assertNull($versionedDummy->next);
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
        $versionedDummy = Dummy::withoutGlobalScopes()->find($dummy->id);

        $this->assertVersioning($dummy, $versionedDummy);

        $dummy = Dummy::updateOrCreate(['id' => '999'], ['name' => 'updated dummy']);
        $versionedDummy = Dummy::withoutGlobalScopes()->skip(1)->find($dummy->id);

        $this->assertVersioning($dummy, $versionedDummy);
    }

    // @todo method first does not return id 1 after it was updated and set deleted_at in versioning
}
