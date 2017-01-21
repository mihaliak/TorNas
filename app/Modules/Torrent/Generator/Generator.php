<?php
namespace TorNas\Modules\Torrent\Generator;

class Generator
{
    /**
     * @var string Magnet link
     */
    protected $magnet;

    /**
     * @var string Path where to store torrent file
     */
    protected $torrent;

    /**
     * @return void
     */
    public function generate()
    {
        shell_exec('python ' . $this->getBinPath() . ' -m "' . $this->magnet . '" --output ' . $this->torrent);
    }

    /**
     * @param string $magnet
     *
     * @return $this
     */
    public function setMagnet($magnet)
    {
        $this->magnet = $magnet;

        return $this;
    }

    /**
     * @return string
     */
    public function getTorrent()
    {
        return $this->torrent;
    }

    /**
     * @param string $torrent
     *
     * @return $this
     */
    public function setTorrent($torrent)
    {
        $this->torrent = $torrent;

        return $this;
    }

    /**
     * @return string
     */
    public function getBinPath()
    {
        return __DIR__ . '/Bin/Magnet2Torrent.py';
    }
}
