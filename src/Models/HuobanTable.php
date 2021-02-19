<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanTable
{

    public static function get($table, $body = null, $options = [])
    {
        return Huoban::execute('GET', "/table/{$table}", $body, $options);
    }

    public static function update($table, $body = null, $options = [])
    {
        return Huoban::execute('PUT', "/table/{$table}", $body, $options);
    }

    public static function copy($table, $body = null, $options = [])
    {
        return Huoban::execute('POST', "/table/{$table}/copy", $body, $options);
    }

    public static function setAlias($table, $body = null, $options = [])
    {
        return Huoban::execute('POST', "/table/{$table}/alias", $body, $options);
    }

    public static function getTables($space_id, $body = null, $options = [])
    {
        return Huoban::execute('GET', "/tables/space/{$space_id}", $body, $options);
    }

    public static function getPermissions($table, $body = null, $options = [])
    {
        return Huoban::execute('POST', "/permissions/table/{$table}", $body, $options);
    }
}
