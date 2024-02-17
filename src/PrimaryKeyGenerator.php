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

    /**
     * @var SequenceResolver
     */
    protected $resolver;

    public function __construct(Snowflake $snowflake, SequenceResolver $resolver)
    {
        $this->snowflake = $snowflake;
        $this->resolver  = $resolver;
    }

    public function generate()
    {
        return $this->snowflake
            ->setSequenceResolver($this->resolver)
            ->id()
        ;
    }
}
