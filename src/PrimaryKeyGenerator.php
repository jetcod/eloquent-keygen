<?php

namespace Jetcod\Eloquent;

class PrimaryKeyGenerator implements PrimaryKeyGeneratorInterface
{
    public function generate()
    {
        return random_int(1000000000, 9999999999);
    }
}
