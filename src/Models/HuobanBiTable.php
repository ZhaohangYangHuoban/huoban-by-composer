<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanBiTable
{
    public $interfaceType = 'bi';
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }
    // 创建数据仓库表
    public function create($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('POST', "/sync_table/space/{$space_id}", $body, $options);
        return $response;
    }
    // 更新数据仓库表
    public function update($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('PUT', "/sync_table/space/{$space_id}", $body, $options);
        return $response;
    }
    // 获取数仓库表
    public function get($table_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('GET', "/table/{$table_id}", $body, $options);
        return $response;
    }
    //删除数据仓库表
    public function delete($space_id, $table_alias, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('delete', "/sync_table/space/{$space_id}/alias/{$table_alias}", $body, $options);
        return $response;
    }

    public function syncFrom($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('POST', "/table/space/{$space_id}/sync_from", $body, $options);
        return $response;
    }
}
