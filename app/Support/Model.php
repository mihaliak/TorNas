<?php
namespace TorNas\Support;

use Webpatser\Uuid\Uuid;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot function from laravel
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
