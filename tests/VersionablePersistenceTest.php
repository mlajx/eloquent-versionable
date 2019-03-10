<?php

namespace Cohrosonline\EloquentVersionable\Test;

use Cohrosonline\EloquentVersionable\Test\Models\Dummy;
use Cohrosonline\EloquentVersionable\Test\Models\Versioning\DummyVersioning;

class VersionablePersistenceTest extends TestCase
{

    /** @test */
    public function it_creates_versioning_register_on_model_create()
    {
        // @todo disable versioning scope
        $dummy = Dummy::first();
        $versionedDummy = DummyVersioning::where('id', $dummy->id)->first();

        $this->assertVersioning($dummy, $versionedDummy);

        $this->assertNotNull($versionedDummy->_id);
        $this->assertNull($versionedDummy->next);
        $this->assertNull($versionedDummy->deleted_at);
    }

    /** @test */
    public function it_creates_versioning_register_on_model_update()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->update(['name' => 'updated again']);
        $firstVersionedDummy = DummyVersioning::where('id', $dummy->id)->first();
        $secondVersionedDummy = DummyVersioning::where('id', $dummy->id)
            ->skip(1)
            ->first();
        $thirdVersionedDummy = DummyVersioning::where('id', $dummy->id)
            ->skip(2)
            ->first();

        $this->assertEquals($firstVersionedDummy->name, '1');
        $this->assertEquals($firstVersionedDummy->next, $secondVersionedDummy->updated_at);

        $this->assertEquals($secondVersionedDummy->name, 'updated');
        $this->assertEquals($secondVersionedDummy->next, $thirdVersionedDummy->updated_at);

        $this->assertVersioning($dummy, $thirdVersionedDummy);
        $this->assertNotNull($thirdVersionedDummy->_id);
        $this->assertNull($thirdVersionedDummy->next);
        $this->assertNull($thirdVersionedDummy->deleted_at);
    }

    /** @test */
    public function it_works_with_soft_delete()
    {
        $dummy = Dummy::first();
        $dummy->update(['name' => 'updated']);
        $dummy->delete();
        $firstVersionedDummy = DummyVersioning::where('id', $dummy->id)->first();
        $secondVersionedDummy = DummyVersioning::where('id', $dummy->id)
            ->skip(1)
            ->first();
        $thirdVersionedDummy = DummyVersioning::where('id', $dummy->id)
            ->skip(2)
            ->withTrashed()
            ->first();

        $this->assertEquals($firstVersionedDummy->name, '1');
        $this->assertEquals($firstVersionedDummy->next, $secondVersionedDummy->updated_at);

        $this->assertEquals($secondVersionedDummy->name, 'updated');
        $this->assertEquals($secondVersionedDummy->next, $thirdVersionedDummy->updated_at);

        $this->assertVersioning($dummy, $thirdVersionedDummy);
        $this->assertNotNull($thirdVersionedDummy->_id);
        $this->assertNull($thirdVersionedDummy->next);
        $this->assertNotNull($thirdVersionedDummy->deleted_at);
    }
}
