<?php
namespace TorNas\Modules\Genre;

use TorNas\Support\Model;

class Genre extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
