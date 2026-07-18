<?php

namespace Jetcod\Eloquent\Test\Feature;

use Jetcod\Eloquent\Model;
use Jetcod\Eloquent\PrimaryKeyGenerator;
use Jetcod\Eloquent\Test\TestCase;
use Mockery as m;

class EloquentModelTest extends TestCase
{
    private $mockedId = '123456789012345678';

    public function testModelGeneratesSnowflakeIdOnlyWhenCreatingNewRecord()
    {
        $this->instantiateMockedPrimaryKeyGenerator();

        $dummyModel = $this->getDummyModelInstance();

        $created = $dummyModel->save();
        $id      = $dummyModel->id;

        $dummyModel->setUpdatedAt(now()->addMinute());

        $updated = $dummyModel->save();

        $this->assertTrue($created);
        $this->assertEquals($this->mockedId, $id);
        $this->assertTrue($updated);
        $this->assertEquals($id, $dummyModel->id);
    }

    public function testModelUsesSnowflakeIdGeneratorOnCreate()
    {
        $this->instantiateMockedPrimaryKeyGenerator();

        $dummyModel = $this->getDummyModelInstance();

        $createdModel = $dummyModel->create();

        $this->assertEquals($this->mockedId, $createdModel->id);
    }

    public function testModelGeneratesSnowflakeIdOnlyForCreatingEvent()
    {
        $this->instantiateMockedPrimaryKeyGenerator();

        $dummyModel = $this->getDummyModelInstance();
        $dispatcher = $dummyModel->getEventDispatcher();
        $modelClass = get_class($dummyModel);

        $dispatcher->dispatch("eloquent.saving: {$modelClass}", $dummyModel);

        $this->assertNull($dummyModel->getKey());

        $dispatcher->dispatch("eloquent.creating: {$modelClass}", $dummyModel);

        $this->assertEquals($this->mockedId, $dummyModel->getKey());
    }

    public function testModelUsesAutoIncrementIdWhileSnowflakeIdGeneratorIsDisabled()
    {
        $dummyModel = $this->getDummyModelInstance(false);

        $saved = $dummyModel->save();

        $this->assertTrue($saved);
        $this->assertEquals(1, $dummyModel->id);
    }

    private function instantiateMockedPrimaryKeyGenerator()
    {
        $generatorMock = m::mock(PrimaryKeyGenerator::class)->makePartial();
        $generatorMock->shouldReceive('generate')->once()->andReturn($this->mockedId);

        $this->app->instance('snowflake-id', $generatorMock);
    }

    private function getDummyModelInstance(bool $snowflake = true)
    {
        return $snowflake ? new class() extends Model {
            protected $table = 'dummy_table';
        } : new class() extends Model {
            protected $table = 'dummy_table';

            protected function snowflake(): bool
            {
                return false;
            }
        };
    }
}
