<?php

namespace Jetcod\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Jetcod\Eloquent\Facades\PrimaryKeyGenerator;

class Model extends EloquentModel
{
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->snowflake() && !$model->getKey()) {
                $model->setIncrementing(false);
                $keyName = $model->getKeyName();
                $id      = PrimaryKeyGenerator::generate();
                $model->setAttribute($keyName, $id);
            }
        });
    }

    protected function snowflake(): bool
    {
        return true;
    }
}
