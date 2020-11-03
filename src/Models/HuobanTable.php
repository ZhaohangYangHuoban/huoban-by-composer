<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanTable
{

    public static function get($table, $body = null, $options = [])
    {
        $url = "/table/{$table}";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('get', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function update($table, $body = null, $options = [])
    {
        $url = "/table/{$table}";

        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('put', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function copy($table, $body = null, $options = [])
    {
        $url = "/table/{$table}/copy";

        $format_data = Huoban::format($url, $body, $options);
        $request =  new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function setAlias($table, $body = null, $options = [])
    {
        $url = "/table/{$table}/alias";

        $format_data = Huoban::format($url, $body, $options);
        $request =  new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function getTables($space_id, $body = null, $options = [])
    {
        $url = "/tables/space/{$space_id}";

        $format_data = Huoban::format($url, $body, $options);
        $request =  new Request('get', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }

    public static function getPermissions($table, $body = null, $options = [])
    {
        $url = "/permissions/table/{$table}";

        $format_data = Huoban::format($url, $body, $options);
        $request =  new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);

        return isset($options['res_type']) && $options['res_type'] == 'request' ? $request : Huoban::requestJsonSync($request);
    }
}
