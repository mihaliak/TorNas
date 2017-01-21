<?php
namespace TorNas\Modules\Statistics;

use TorNas\Modules\Category\Category;
use TorNas\Support\Transformer;

class StatisticsTransformer extends Transformer
{
    /**
     * Transform item
     *
     * @param \TorNas\Modules\Statistics\Statistics $item
     *
     * @return mixed
     */
    public function make($item)
    {
        $memory       = $item->memory();
        $disk         = $item->disk();
        $transmission = $item->transmission();
        $torrents     = $transmission->get('torrents');

        $torrents['percentage'] = [
            'active' => $torrents['total'] > 0 ? ($torrents['active'] / $torrents['total']) * 100 : 0,
            'paused' => $torrents['total'] > 0 ? ($torrents['paused'] / $torrents['total']) * 100 : 0,
        ];

        $filesPerEachCategory = Category::all()->map(function ($category) use ($item) {
            $directory = storage_path('transmission/files/'. $category->directory);

            if (file_exists($directory)) {
                return count($item->files($directory));
            }

            return 0;
        });

        return [
            'files'        => $filesPerEachCategory->sum(),
            'memory'       => [
                'used'       => $this->formatBytes($memory->get('used')),
                'total'      => $this->formatBytes($memory->get('total')),
                'usage'      => $memory->get('usage'),
                'percentage' => ($memory->get('used') / $memory->get('total')) * 100,
            ],
            'disk'         => [
                'used'       => $this->formatBytes($disk->get('total') - $disk->get('free')),
                'free'       => $this->formatBytes($disk->get('free')),
                'total'      => $this->formatBytes($disk->get('total')),
                'percentage' => (($disk->get('total') - $disk->get('free')) / $disk->get('total')) * 100,
            ],
            'cpu'          => $item->cpu(),
            'uptime'       => $item->uptime(),
            'transmission' => [
                'torrents' => $torrents,
                'download' => [
                    'value'      => $this->formatBytes($transmission->get('download')) . '/s',
                    'percentage' => ($transmission->get('download') / 30000000) * 100,
                ],
                'upload'   => [
                    'value'      => $this->formatBytes($transmission->get('upload')) . '/s',
                    'percentage' => ($transmission->get('upload') / 5000000) * 100,
                ],
            ],
        ];
    }
}
