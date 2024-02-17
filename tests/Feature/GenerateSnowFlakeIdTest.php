<?php

namespace Jetcod\Eloquent\Test\Feature;

use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Jetcod\Eloquent\PrimaryKeyGenerator;
use Jetcod\Eloquent\Test\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GenerateSnowFlakeIdTest extends TestCase
{
    public function testCreatesFixedLengthStringId()
    {
        $snowflake = new Snowflake(1, 2);
        $generator = new PrimaryKeyGenerator($snowflake, new RandomSequenceResolver());
        $id        = $generator->generate();

        $this->assertIsString($id);
        $this->assertEquals(18, strlen($id));
    }

    public function testCreatesUniqueSnowflakeId()
    {
        $ids       = [];
        $snowflake = new Snowflake(1, 2);
        $generator = new PrimaryKeyGenerator($snowflake, new RandomSequenceResolver());

        for ($i = 0; $i < 10000; ++$i) {
            $ids[] = $generator->generate();
        }

        $this->assertCount(count(array_unique($ids)), $ids);
    }
}
