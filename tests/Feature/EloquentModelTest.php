<?php

namespace Jetcod\Eloquent\Test\Feature;

use Jetcod\Eloquent\Model;
use Jetcod\Eloquent\PrimaryKeyGenerator;
use Jetcod\Eloquent\Test\TestCase;
use Mockery as m;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class EloquentModelTest extends TestCase
{
    private $mockedId = '123456789012345678';

    public function testModelUsesSnowflakeIdGeneratorOnSave()
    {
        $this->instantiateMockedPrimaryKeyGenerator();

        $dummyModel = $this->getDummyModelInstance();

        $saved = $dummyModel->save();

        $this->assertTrue($saved);
        $this->assertEquals($this->mockedId, $dummyModel->id);
    }

    public function testModelUsesSnowflakeIdGeneratorOnCreate()
    {
        $this->instantiateMockedPrimaryKeyGenerator();

        $dummyModel = $this->getDummyModelInstance();

        $createdModel = $dummyModel->create();

        $this->assertEquals($this->mockedId, $createdModel->id);
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
