<?php
namespace TorNas\Modules\Torrent;

use Illuminate\Support\Collection;
use Transmission\Transmission;

class TorrentService
{
    /**
     * @var Transmission
     */
    private $transmission;

    /**
     * TorrentService constructor.
     *
     * @param Transmission $transmission
     */
    public function __construct(Transmission $transmission)
    {
        $this->transmission = $transmission;
    }

    /**
     * Get list of torrents in transmission
     *
     * @return Collection
     */
    public function get()
    {
        return new Collection($this->transmission->all());
    }

    /**
     * @param string $hash
     *
     * @return \Transmission\Model\Torrent
     */
    public function find($hash)
    {
        return $this->transmission->get($hash);
    }

    /**
     * Add torrent to transmission
     *
     * @param string $torrent Path to torrent file
     * @param string $path Path where to save torrent
     *
     * @return \Transmission\Model\Torrent
     */
    public function add($torrent, $path)
    {
        return $this->transmission->add($torrent, false, $path);
    }

    /**
     * Remove torrent
     *
     * @param string|array $hash
     * @param bool   $files
     *
     * @return void
     */
    public function remove($hash, $files = false)
    {
        if (is_array($hash)) {
            $hash = array_map(function ($torrent) {
                return $this->find($torrent);
            }, $hash);
        } else {
            $hash = $this->find($hash);
        }

        return $this->transmission->remove(
            $hash,
            $files
        );
    }

    /**
     * Start torrent download
     *
     * @param string $hash
     *
     * @return void
     */
    public function start($hash)
    {
        return $this->transmission->start(
            $this->find($hash),
            true
        );
    }

    /**
     * Stop torrent download
     *
     * @param string $hash
     *
     * @return void
     */
    public function stop($hash)
    {
        return $this->transmission->stop(
            $this->find($hash)
        );
    }

    /**
     * Get transmission client stats
     *
     * @return \Transmission\Model\Stats\Session
     */
    public function stats()
    {
        return $this->transmission->getSessionStats();
    }
}
