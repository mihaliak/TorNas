<?php
namespace TorNas\Modules\Torrent\Transformers;

use TorNas\Support\Transformer;

class TorrentTransformer extends Transformer
{
    /**
     * Transform item
     *
     * @param \Transmission\Model\Torrent $item
     *
     * @return mixed
     */
    public function make($item)
    {
        return [
            'id' => $item->getId(),
            'size'   => $this->formatBytes($item->getSize()),
            'name'   => $item->getName(),
            'hash'   => $item->getHash(),
            'status' => [
                'value'          => $item->getStatus(),
                'is_finished'    => $item->isFinished(),
                'is_downloading' => $item->isDownloading(),
            ],
            'stats'  => [
                'peers'      => $item->getPeersConnected(),
                'download'   => $this->formatBytes($item->getDownloadRate()),
                'percentage' => $item->getPercentDone(),
                'eta'        => $item->getPercentDone() != 100 ? gmdate('H:i:s', $item->getEta()) : '00:00:00',
            ],
            'files'  => collect($item->getFiles())->map(function ($item) {
                return [
                    'name' => $item->getName(),
                    'size' => $this->formatBytes($item->getSize()),
                ];
            }),
        ];
    }
}
