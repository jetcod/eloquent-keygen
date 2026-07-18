<?php

namespace Jetcod\Eloquent\Test\Feature;

use Godruoyi\Snowflake\FileLockResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\RedisSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Jetcod\Eloquent\PrimaryKeyGenerator;
use Jetcod\Eloquent\Test\TestCase;
use Mockery as m;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class GenerateSnowflakeIdTest extends TestCase
{
    public function testFacadeAndContainerReturnSameInstance()
    {
        $this->assertEquals(app('snowflake-id'), app(PrimaryKeyGenerator::class));
    }

    public function testCreatesFixedLengthStringId()
    {
        $snowflake = app(Snowflake::class, ['datacenter' => 1, 'workerId' => 2]);
        $generator = app(PrimaryKeyGenerator::class, ['snowflake' => $snowflake, 'resolver' => new RandomSequenceResolver()]);
        $id        = $generator->generate();

        $this->assertIsString($id);
        $this->assertEquals(18, strlen($id));
    }

    public function testCreatesUniqueSnowflakeIdsByRandomSequenceResolver()
    {
        $ids       = [];
        $snowflake = app(Snowflake::class, ['datacenter' => 1, 'workerId' => 2]);
        $generator = app(PrimaryKeyGenerator::class, ['snowflake' => $snowflake, 'resolver' => new RandomSequenceResolver()]);

        for ($i = 0; $i < 10000; ++$i) {
            $ids[] = $generator->generate();
        }

        $this->assertCount(count(array_unique($ids)), $ids);
    }

    public function testCreatesUniqueSnowflakeIdsByFileLockSequenceResolver()
    {
        $ids       = [];
        $snowflake = app(Snowflake::class, ['datacenter' => 1, 'workerId' => 2]);
        $generator = app(PrimaryKeyGenerator::class, ['snowflake' => $snowflake, 'resolver' => new FileLockResolver(sys_get_temp_dir())]);

        for ($i = 0; $i < 10000; ++$i) {
            $ids[] = $generator->generate();
        }

        $this->assertCount(count(array_unique($ids)), $ids);
    }

    public function testCreatesUniqueSnowflakeIdsBySequenceResolver()
    {
        $ids       = [];
        $snowflake = app(Snowflake::class, ['datacenter' => 1, 'workerId' => 2]);

        $resolverMock = m::mock(RedisSequenceResolver::class);
        $resolverMock->shouldReceive('sequence')->andReturnUsing(function () {
            static $sequence = 0;

            return $sequence++;
        });

        $generator = app(PrimaryKeyGenerator::class, ['snowflake' => $snowflake, 'resolver' => $resolverMock]);

        for ($i = 0; $i < 100; ++$i) {
            $ids[] = $generator->generate();
        }

        $this->assertCount(count(array_unique($ids)), $ids);
    }
}
