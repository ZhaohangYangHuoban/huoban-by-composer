<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanShare
{
    public static function itemCreate($item_id, $body = [], $options = [])
    {
        return Huoban::execute('POST', "/item_share/item/{$item_id}", $body, $options);
    }

    public static function itemGet($item_share_id = [], $body = [], $options = [])
    {
        return Huoban::execute('GET', "/item_share/{$item_share_id}", $body, $options);
    }

    public static function itemUpdate($item_share = [], $options = [])
    {
        $item_share_id = $item_share['item_share_id'];
        $body          = $item_share;
        return Huoban::execute('PUT', "/item_share/{$item_share_id}", $body, $options);
    }

    public static function itemOpen($item_share, $options = [])
    {
        $item_share['status'] = 'enable';
        return self::itemUpdate($item_share, $options);
    }
    public static function itemClose($item_share, $options = [])
    {
        $item_share['status'] = 'disable';
        return self::itemUpdate($item_share, $options);
    }
}
