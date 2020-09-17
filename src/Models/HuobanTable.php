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
        return new Request('get', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function getExc($table, $body = null, $options = [])
    {
        $request = self::get($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    public static function update($table, $body = null, $options = [])
    {
        $url = "/table/{$table}";

        $format_data = Huoban::format($url, $body, $options);
        return new Request('put', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function updateExc($table, $body = null, $options = [])
    {
        $request = self::update($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    public static function copy($table, $body = null, $options = [])
    {
        $url = "/table/{$table}/copy";

        $format_data = Huoban::format($url, $body, $options);
        return new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function copyExc($table, $body = null, $options = [])
    {
        $request = self::copy($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    public static function setAlias($table, $body = null, $options = [])
    {
        $url = "/table/{$table}/alias";

        $format_data = Huoban::format($url, $body, $options);
        return new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function setAliasExc($table, $body = null, $options = [])
    {
        $request = self::setAlias($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    public static function getTables($space_id, $body = null, $options = [])
    {
        $url = "/tables/space/{$space_id}";

        $format_data = Huoban::format($url, $body, $options);
        return new Request('get', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function getTablesExc($space_id, $body = null, $options = [])
    {
        $request = self::getTables($space_id, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    public static function getPermissions($table, $body = null, $options = [])
    {
        $url = "/permissions/table/{$table}";

        $format_data = Huoban::format($url, $body, $options);
        return new Request('post', $format_data['url'], $format_data['headers'], $format_data['body']);
    }
    public static function getPermissionsExc($table, $body = null, $options = [])
    {
        $request = self::getPermissions($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }
    public static function getAliasFieldsExc($table, $body = null, $options = [])
    {
        $request = self::getExc($table, $body, $options);
        $table = Huoban::requestJsonSync($request);
        // $fields = [];
        // if ($table && $table['fields']) {
        //     foreach ($table['fields'] as $key => $value) {

        //         if ($value['app_id'] != $app_id) {
        //             continue;
        //         }
        //         $fields[$value['field_id']]          = $value;
        //         $fields[$value['application_alias']] = $value;
        //     }
        // }
        // return $fields;
    }
}
