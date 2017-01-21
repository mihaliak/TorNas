<?php
namespace TorNas\Support;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Transformer
{
    /**
     * Transform item
     *
     * @param Model $item
     *
     * @return mixed
     */
    abstract public function make($item);

    /**
     * Transform data
     *
     * @param $data
     *
     * @return mixed
     */
    public function transform($data)
    {
        if ($data instanceof LengthAwarePaginator) {
            $data->setCollection(
                $data->getCollection()->map(function ($item) {
                    return $this->make($item);
                })
            );

            return $data;
        }

        if ($data instanceof Collection) {
            return $data->map(function ($item) {
                return $this->make($item);
            });
        }

        return $this->make($data);
    }

    /**
     * Format bytes to human readable size
     *
     * @param  int $bytes
     * @param  int $precision
     *
     * @return string
     */
    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0); 
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow   = min($pow, count($units) - 1); 

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
}
