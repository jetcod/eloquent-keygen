<?php

namespace Jetcod\Eloquent;

use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;

class PrimaryKeyGenerator
{
    /**
     * @var Snowflake;
     */
    protected $snowflake;

    public function __construct(Snowflake $snowflake)
    {
        $this->snowflake = $snowflake;
    }

    public function generate()
    {
        return $this->snowflake
            ->setSequenceResolver(SequenceResolver::class)
            ->id()
        ;
    }
}
