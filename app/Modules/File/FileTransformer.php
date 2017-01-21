<?php
namespace TorNas\Modules\File;

use Spatie\MediaLibrary\Media;

use TorNas\Support\Transformer;

class FileTransformer extends Transformer
{
    /**
     * Transform item
     *
     * @param File $item
     *
     * @return mixed
     */
    public function make($item)
    {
        return [
            'id'       => $item->id,
            'hash'     => $item->hash,
            'name'     => $item->name,
            'episode'  => $item->episode,
            'cover'    => asset($item->getFirstMediaUrl('cover')),
            'size'     => [
                'value' => (int) $item->size,
                'human' => $this->formatBytes($item->size)
            ],
            'rating'   => $item->rating,
            'year'     => $item->year,
            'runtime'  => (int) $item->runtime,
            'status'   => [
                'is_finished' => $item->is_finished,
                'is_paused'   => $item->is_paused,
            ],
            'file'     => [
                'name'     => $item->file,
                'location' => str_replace(base_path('storage/transmission'), '', $item->location),
            ],
            'added'    => [
                'human'  => $item->created_at->diffForHumans(),
                'full'   => $item->created_at->format('d. F Y H:i'),
                'value'  => $item->created_at->timestamp,
            ],
            'updated'    => [
                'human' => $item->updated_at->diffForHumans(),
                'full'  => $item->updated_at->format('d. F Y H:i'),
                'value'  => $item->updated_at->timestamp,
            ],
            'added_by' => $item->user->login,
            'category' => [
                'name' => $item->category->name,
                'value' => $item->category->apiValue
            ],
            'genres'   => $item->genres->map(function ($item) {
                return $item->name;
            })->toArray(),
            'subtitles' => $item->getMedia('subtitles')->map(function (Media $subtitle) {
                return [
                    'id'   => $subtitle->id,
                    'file' => str_replace(base_path('storage/transmission'), '', $subtitle->getPath()),
                    'lang' => $subtitle->getCustomProperty('lang'),
                    'episode' => $subtitle->getCustomProperty('episode'),
                ];
            })->toArray()
        ];
    }
}
