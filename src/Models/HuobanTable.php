<?php

namespace Huoban\Models;

use Huoban\HuobanBasic;
use Huoban\Models\Package\Table;

class HuobanTable extends HuobanBasic
{
    use Table;

    /**
     * 获取表结构
     *
     * @param [type] $table
     * @param [type] $body
     * @param array $options
     * @return void
     */
    public function get($table, $body = null, $options = [])
    {
        return $this->request->execute('GET', "/table/{$table}", $body, $options);
    }
    /**
     * 创建表
     *
     * @param [type] $space_id
     * @param [type] $body
     * @param array $options
     * @return void
     */
    public function create($space_id, $body = null, $options = [])
    {
        return $this->request->execute('POST', "/table/space/{$space_id}", $body, $options);

        /**
         * 创建格式化body实例
         */
//        $fields[] = $this->getFieldTextBasic('test', 'field_A');
        //        $body     = $this->getTableBasic('test1', 'table_test1', $fields);
    }

    public function update($table, $body = null, $options = [])
    {
        return $this->request->execute('PUT', "/table/{$table}", $body, $options);
    }

    public function copy($table, $body = null, $options = [])
    {
        return $this->request->execute('POST', "/table/{$table}/copy", $body, $options);
    }

    public function setAlias($table, $body = null, $options = [])
    {
        return $this->request->execute('POST', "/table/{$table}/alias", $body, $options);
    }

    public function getTables($space_id, $body = null, $options = [])
    {
        return $this->request->execute('GET', "/tables/space/{$space_id}", $body, $options);
    }

    public function getPermissions($table, $body = null, $options = [])
    {
        return $this->request->execute('POST', "/permissions/table/{$table}", $body, $options);
    }
}
