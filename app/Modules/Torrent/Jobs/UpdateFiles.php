<?php
namespace TorNas\Modules\Torrent\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

use Transmission\Model\Torrent;

use TorNas\Modules\File\File;

class UpdateFiles implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    public $torrents;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $torrents)
    {
        $this->torrents = $torrents;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->torrents->each(function (Torrent $torrent) {
            File::where('hash', $torrent->getHash())->update([
                'file'        => $torrent->getName(),
                'size'        => $torrent->getSize(),
                'is_finished' => $torrent->isFinished(),
                'is_paused'   => ! $torrent->isFinished() && ! $torrent->isDownloading(),
                'location'    => $torrent->getDownloadDir(),
            ]);
        });
    }
}
