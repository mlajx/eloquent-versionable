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

        $this->assertEquals($dummy->id, $versionedDummy->id);
        $this->assertEquals($dummy->name, $versionedDummy->name);
        $this->assertEquals($dummy->created_at, $versionedDummy->created_at);
        $this->assertEquals($dummy->updated_at, $versionedDummy->updated_at);

        $this->assertNull($dummy->deleted_at);

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

        $this->assertEquals($dummy->id, $thirdVersionedDummy->id);
        $this->assertEquals($dummy->name, $thirdVersionedDummy->name);
        $this->assertEquals($dummy->created_at, $thirdVersionedDummy->created_at);
        $this->assertEquals($dummy->updated_at, $thirdVersionedDummy->updated_at);
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

        $this->assertNotNull($dummy->deleted_at);

        $this->assertEquals($firstVersionedDummy->name, '1');
        $this->assertEquals($firstVersionedDummy->next, $secondVersionedDummy->updated_at);

        $this->assertEquals($secondVersionedDummy->name, 'updated');
        $this->assertEquals($secondVersionedDummy->next, $thirdVersionedDummy->updated_at);

        $this->assertEquals($dummy->id, $thirdVersionedDummy->id);
        $this->assertEquals($dummy->name, $thirdVersionedDummy->name);
        $this->assertEquals($dummy->created_at, $thirdVersionedDummy->created_at);
        $this->assertEquals($dummy->updated_at, $thirdVersionedDummy->updated_at);
        $this->assertNotNull($thirdVersionedDummy->_id);
        $this->assertNull($thirdVersionedDummy->next);
        $this->assertNotNull($thirdVersionedDummy->deleted_at);
    }
}
