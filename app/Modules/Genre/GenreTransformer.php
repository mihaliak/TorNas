<?php
namespace TorNas\Modules\Genre;

use TorNas\Support\Transformer;

class GenreTransformer extends Transformer
{
    /**
     * Transform item
     *
     * @param Genre $item
     *
     * @return mixed
     */
    public function make($item)
    {
        return [
            'id'       => $item->id,
            'name'     => $item->name,
            'apiValue' => $item->apiValue,
        ];
    }
}
