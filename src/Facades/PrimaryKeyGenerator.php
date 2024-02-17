<?php

namespace Jetcod\Eloquent\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int generate()
 */
class PrimaryKeyGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'snowflake-id';
    }
}
