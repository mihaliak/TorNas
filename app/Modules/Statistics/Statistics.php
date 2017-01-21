<?php
namespace TorNas\Modules\Statistics;

use FilesystemIterator;
use Illuminate\Support\Collection;

use TorNas\Modules\Torrent\TorrentService;

class Statistics
{
    /**
     * @var TorrentService
     */
    private $service;

    /**
     * Statistics constructor.
     */
    public function __construct(TorrentService $service)
    {
        $this->service = $service;
    }

    /**
     * Returns memory used, total space and percentage usage
     *
     * @return \Illuminate\Support\Collection
     */
    public function memory()
    {
        $memory = collect(explode(' ', trim(shell_exec('free'))))
            ->filter()
            ->values()
            ->filter(function ($item, $key) {
                return $key == 6 || $key == 7;
            })
            ->values();

        return new Collection([
            'used'  => $memory->last() * 1000,
            'total' => $memory->first() * 1000,
            'usage' => number_format($memory->last() / $memory->first() * 100, 2) . '%',
        ]);
    }

    /**
     * Returns disk free and total space
     *
     * @return \Illuminate\Support\Collection
     */
    public function disk()
    {
        return new Collection([
            'free'  => disk_free_space('/'),
            'total' => disk_total_space('/'),
        ]);
    }

    /**
     * Returns cpu percentage usage
     *
     * @return string
     */
    public function cpu()
    {
        $average = sys_getloadavg();
        $cores   = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));

        return round($average[0] / ($cores + 1) * 100, 0);
    }

    /**
     * Returns system uptime
     *
     * @return \Illuminate\Support\Collection
     */
    public function uptime()
    {
        $str = file_get_contents('/proc/uptime');
        $num = floatval($str);

        $secs = round(fmod($num, 60));

        $num  = intdiv($num, 60);
        $mins = $num % 60;

        $num   = intdiv($num, 60);
        $hours = $num % 24;

        $days = intdiv($num, 24);

        return new Collection(compact('days', 'hours', 'mins', 'secs'));
    }

    /**
     * Returns files and directories in directory
     *
     * @param string $directory
     *
     * @return Collection
     */
    public function files($directory)
    {
        $iterator = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);

        return new Collection($iterator);
    }

    /**
     * Returns transmission statistics
     *
     * @return Collection
     */
    public function transmission()
    {
        $stats = $this->service->stats();

        return new Collection([
            'torrents' => [
                'active' => $stats->getActiveTorrentCount(),
                'paused' => $stats->getPausedTorrentCount(),
                'total'  => $stats->getTorrentCount(),
            ],
            'download' => $stats->getDownloadSpeed(),
            'upload'   => $stats->getUploadSpeed(),
        ]);
    }
}
