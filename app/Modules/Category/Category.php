<?php
namespace TorNas\Modules\Category;

use TorNas\Support\Model;
use TorNas\Modules\File\File;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'directory'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * @return string
     */
    public function getTorrentDirectory()
    {
        return 'transmission/torrents/' . $this->directory;
    }

    /**
     * @return string
     */
    public function getFileDirectory()
    {
        return 'transmission/files/'.$this->directory;
    }
}
