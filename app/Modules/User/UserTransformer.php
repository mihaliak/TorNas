<?php
namespace TorNas\Modules\User;

use TorNas\Support\Model;
use TorNas\Support\Transformer;

class UserTransformer extends Transformer
{

    /**
     * Transform item
     *
     * @param Model $item
     *
     * @return mixed
     */
    public function make($item)
    {
        return [
            'id'   => $item->id,
            'name' => $item->login,
        ];
    }
}
