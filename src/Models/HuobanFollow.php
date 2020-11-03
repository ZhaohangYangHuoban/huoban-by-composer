<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanFollow
{

    public static function create($item_id, $body = [], $options = [])
    {
        $url = "/follow/item/{$item_id}";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('POST', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }
    public static function delete($ref_id, $body = [], $options = [])
    {
        $url = "/follow/item/{$ref_id}";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('delete', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }
    public static function getAll($item_id, $body = [], $options = [])
    {
        $url = "/follow/item/{$item_id}/find";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('POST', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }
}
