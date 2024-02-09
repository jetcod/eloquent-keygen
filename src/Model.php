<?php

namespace Jetcod\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Jetcod\Eloquent\Facades\PrimaryKeyGenerator;

class Model extends EloquentModel
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $primaryKey = $model->primaryKey;

            $model->{$primaryKey} = PrimaryKeyGenerator::generate();
        });
    }
}
