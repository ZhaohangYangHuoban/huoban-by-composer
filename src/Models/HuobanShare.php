<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanShare
{
    public static function itemCreate($item_id, $body = [], $options = [])
    {
        $url = "/item_share/item/{$item_id}";
        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);
        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function itemGet($item_share_id = [], $body = [], $options = [])
    {
        $url = "/item_share/{$item_share_id}";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('get', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function itemUpdate($item_share = [], $options = [])
    {
        $item_share_id = $item_share['item_share_id'];

        $url = "/item_share/{$item_share_id}";
        $body = $item_share;

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('put', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
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
