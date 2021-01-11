<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanFollow
{

    public static function create($item_id, $body = [], $options = [])
    {
        return Huoban::execute('POST', "/follow/item/{$item_id}", $body, $options);
    }
    public static function delete($ref_id, $body = [], $options = [])
    {
        return Huoban::execute('POST', "/follow/item/{$ref_id}", $body, $options);
    }
    public static function getAll($item_id, $body = [], $options = [])
    {
        return Huoban::execute('POST', "/follow/item/{$item_id}/find", $body, $options);
    }
}
