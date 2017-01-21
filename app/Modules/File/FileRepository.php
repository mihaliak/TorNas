<?php
namespace TorNas\Modules\File;

use Exception;
use Illuminate\Support\Facades\File as FS;
use Transmission\Model\Torrent;

use TorNas\Modules\Torrent\Generator\Generator;
use TorNas\Modules\Torrent\TorrentService;

class FileRepository
{
    /**
     * @var File
     */
    public $file;

    /**
     * @var Torrent|null
     */
    public $torrent;

    /**
     * @var TorrentService
     */
    private $service;

    /**
     * FileRepository constructor.
     *
     * @param File           $file
     * @param TorrentService $service
     */
    public function __construct(File $file, TorrentService $service)
    {
        $this->file    = $file;
        $this->service = $service;
    }

    /**
     * Create new file
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function addFile($attributes)
    {
        $this->file = $this->file->create($attributes);

        return $this;
    }

    /**
     * Update file data
     *
     * @param string $id
     * @param array $attributes
     *
     * @return $this
     */
    public function updateFile($id, $attributes)
    {
        $this->file = $this->file->findOrFail($id);

        $this->file->update($attributes);

        return $this;
    }

    /**
     * Sync file genres
     *
     * @param array $genres
     *
     * @return $this
     */
    public function syncGenres($genres)
    {
        $this->file->genres()->sync($genres);

        return $this;
    }

    /**
     * Get slug for file name
     *
     * @return string
     */
    public function getSlug()
    {
        return str_slug($this->file->name);
    }

    /**
     * Remove cover from file
     *
     * @return $this
     */
    public function clearCover()
    {
        $this->file->clearMediaCollection('cover');

        return $this;
    }

    /**
     * Add cover to file
     *
     * @param string $cover
     *
     * @return $this
     */
    public function addCover($cover)
    {
        try {
            $baseName = basename(parse_url($cover, PHP_URL_PATH));
            $ext      = pathinfo($baseName, PATHINFO_EXTENSION);

            $this->file->addMediaFromUrl($cover)
                       ->usingName($this->getSlug())
                       ->usingFileName($this->getSlug().'.'.$ext)
                       ->toMediaLibrary('cover');
        } catch (Exception $e) {
            $this->file->addMedia(resource_path('assets/images/no-cover.png'))
                       ->preservingOriginal()
                       ->usingName($this->getSlug())
                       ->usingFileName($this->getSlug().'.png')
                       ->toMediaLibrary('cover');
        }

        return $this;
    }

    /**
     * Add subtitles to file
     *
     * @param string $langKey
     * @param string $subtitlesKey
     *
     * @return $this
     */
    public function addSubtitles($langKey, $subtitlesKey, $episodeKey)
    {
        if (request()->has($langKey) && request()->hasFile($subtitlesKey)) {
            $ext     = request()->file($subtitlesKey)->getClientOriginalExtension();
            $lang    = request($langKey);
            $episode = request($episodeKey);

            $this->file->addMediaFromRequest($subtitlesKey)
                       ->usingName($this->getSlug() . '-'. time() .'-' . $lang)
                       ->usingFileName($this->getSlug() . '-'. time() .'-' . $lang.'.'.$ext)
                       ->withCustomProperties(compact('lang', 'episode'))
                       ->toCollectionOnDisk('subtitles', 'subtitles');
        }

        return $this;
    }

    /**
     * Add store torrent file and add torrent to transmission
     *
     * @param string $type
     * @param string $categoryDirectory
     *
     * @return $this
     */
    public function addTorrent($type, $categoryDirectory)
    {
        if ($type == 'file') {
            $torrentFile = $this->file->addMediaFromRequest('torrent');
        } else {
            $torrentGenerator = new Generator();
            $torrentGenerator->setTorrent(storage_path('app/tmp.torrent'))
                             ->setMagnet(request()->get('magnet'))
                             ->generate();

            $torrentFile = $this->file->addMedia(storage_path('app/tmp.torrent'));
        }

        $torrentFile = $torrentFile->usingName($this->getSlug())
                                   ->usingFileName($this->getSlug().'.torrent')
                                   ->toCollectionOnDisk('torrent', 'torrents');

        $this->torrent = $this->service->add(
            $torrentFile->getPath(),
            storage_path($categoryDirectory)
        );

        return $this;
    }

    /**
     * Update file with torrent data
     *
     * @return $this
     */
    public function updateTorrentData()
    {
        $this->file->update([
            'hash'        => $this->torrent->getHash(),
            'file'        => $this->torrent->getName(),
            'size'        => $this->torrent->getSize() ?: 0,
            'is_finished' => $this->torrent->isFinished(),
            'location'    => $this->torrent->getDownloadDir() ?: '',
        ]);

        return $this;
    }

    /**
     * Toggle torrent and file status
     *
     * @param string $hash
     *
     * @return string
     * @throws Exception
     */
    public function toggle($hash)
    {
        $file = $this->file->where('hash', $hash)->firstOrFail();

        if ($file->is_finished) {
            throw new Exception('File already finished.');
        }

        if ($file->is_paused) {
            try {
                $this->service->start($hash);

                $file->is_paused = false;
                $file->save();
            } catch (Exception $e) {
                $file->is_paused = true;
                $file->save();
            }
        } else {
            try {
                $this->service->stop($hash);

                $file->is_paused = true;
                $file->save();
            } catch (Exception $e) {
                $file->is_paused = false;
                $file->save();
            }
        }

        return $file->is_paused ? 'stopped' : 'started';
    }

    /**
     * Get sorted files
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSorted()
    {
        return $this->file->orderBy('created_at', 'desc')->get();
    }

    /**
     * Remove file model and torrent with data
     *
     * @param string $id
     *
     * @return bool|null
     */
    public function remove($id)
    {
        $this->file = $this->file->findOrFail($id);

        try {
            $this->service->remove($this->file->hash, true);
        } catch (Exception $e) {
            //
        }

        // Just in case torrent was removed first without removing files
        if (file_exists($this->file->location . '/' . $this->file->file) && $this->file->location && $this->file->file) {
            FS::deleteDirectory($this->file->location . '/' . $this->file->file);
        }

        return $this->file->delete();
    }

    /**
     * Find file by id
     *
     * @param string $id
     *
     * @return File|$this
     */
    public function find($id, $return = true)
    {
        $this->file = $this->file->findOrFail($id);

        if ($return) {
            return $this->file;
        }

        return $this;
    }

    /**
     * Create new instance of repository
     *
     * @return FileRepository
     */
    public function new()
    {
        return new self($this->file->newInstance(), $this->service);
    }

    /**
     * Check if file exists if it does assign it to file property
     *
     * @param array ...$attributes
     *
     * @return bool
     */
    public function assignWhenExists(...$attributes)
    {
        $file = $this->file->where(...$attributes)->first();

        if ($file) {
            $this->file = $file;

            return true;
        }

        return false;
    }
}
