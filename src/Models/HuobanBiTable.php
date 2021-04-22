<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanBiTable
{
    // 创建数据仓库表
    public static function create($space_id, $body = null, $options = [])
    {
        Huoban::setHttpClient('bi');
        $response = Huoban::execute('POST', "/sync_table/space/{$space_id}", $body, $options);
        Huoban::setHttpClient('api');
        return $response;
    }
    // 更新数据仓库表
    public static function update($space_id, $body = null, $options = [])
    {
        Huoban::setHttpClient('bi');
        $response = Huoban::execute('PUT', "/sync_table/space/{$space_id}", $body, $options);
        Huoban::setHttpClient('api');
        return $response;
    }
    // 获取数仓库表
    public static function get($table_id, $body = null, $options = [])
    {
        Huoban::setHttpClient('bi');
        $response = Huoban::execute('GET', "/table/{$table_id}", $body, $options);
        Huoban::setHttpClient('api');
        return $response;
    }
    //删除数据仓库表
    public static function delete($space_id, $table_alias, $body = null, $options = [])
    {
        Huoban::setHttpClient('bi');
        $response = Huoban::execute('delete', "/sync_table/space/{$space_id}/alias/{$table_alias}", $body, $options);
        Huoban::setHttpClient('api');
        return $response;
    }

    public static function syncFrom($space_id, $body = null, $options = [])
    {
        Huoban::setHttpClient('bi');
        $response = Huoban::execute('POST', "/table/space/{$space_id}/sync_from", $body, $options);
        Huoban::setHttpClient('api');
        return $response;
    }
}
