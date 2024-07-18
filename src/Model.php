<?php

namespace Jetcod\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    use Traits\SnowflakeID;
}
