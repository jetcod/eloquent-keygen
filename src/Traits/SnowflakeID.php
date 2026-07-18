<?php

namespace Jetcod\Eloquent\Traits;

use Jetcod\Eloquent\Facades\PrimaryKeyGenerator;

trait SnowflakeID
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
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
