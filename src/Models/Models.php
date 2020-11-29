<?php

namespace Akrad\Bridage\Models;

use Illuminate\User;
use Illuminate\Database\Eloquent\Model;


class Models
{
    protected static $models = [];

    public static function makeModel($model)
    {
        $model = self::className($model);
        return new $model();
    }

    public static function className($model)
    {
        if(isset(static::$models[$model]))
        {
            $model = static::$models[$model];
        }

        return $model;
    }  
}