<?php

namespace Huoban\Models;

use Huoban\Huoban;

/**
 * BI Table类
 */
class HuobanBiTable
{
    public $interfaceType = 'bi';
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    /**
     * 创建数据仓库表
     *
     * @param int $space_id
     * @param array $body
     * @param array $options
     * @return void
     */
    public function create($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('POST', "/sync_table/space/{$space_id}", $body, $options);
        return $response;
    }

    /**
     * 更新数据仓库表
     *
     * @param int $space_id
     * @param array $body
     * @param array $options
     * @return void
     */
    public function update($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('PUT', "/sync_table/space/{$space_id}", $body, $options);
        return $response;
    }

    /**
     * 获取数仓库表
     *
     * @param int $table_id
     * @param array $body
     * @param array $options
     * @return void
     */
    public function get($table_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('GET', "/table/{$table_id}", $body, $options);
        return $response;
    }

    /**
     * 删除数据仓库表
     *
     * @param int $space_id
     * @param [string] $table_alias
     * @param array $body
     * @param array $options
     * @return void
     */
    public function delete($space_id, $table_alias, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('delete', "/sync_table/space/{$space_id}/alias/{$table_alias}", $body, $options);
        return $response;
    }

    /**
     * 应用同步注册，只需要执行一次
     *
     * @return void
     */
    public function biRegister($table_alias)
    {
        // 注册同步机制
        $body = [
            'space_id'    => $this->_huoban->config['space_id'],
            'table_alias' => $table_alias,
            'safeguard'   => [
                'type'  => 'create_time',
                'cycle' => 30,
            ],
        ];
        $response = $this->_huoban->_bi->register($body);
        return $response;
    }

    /**
     * 立即执行同步数据动作
     *
     * @param [type] $date_time
     * @return void
     */
    public function uploadExecuteImmediately($date_time)
    {
        $response = [];

        $body = [
            'type'         => 'sync',
            'space_id'     => $this->_huoban->config['space_id'],
            'sync_version' => $date_time,
        ];

        $response[] = static::$_huoban->_bi->sync($body);

        $body = [
            'type'         => 'calculate',
            'space_id'     => $this->_huoban->config['space_id'],
            'sync_version' => $date_time,
        ];

        $response[] = static::$_huoban->_bi->sync($body);

        return $response;

    }

    /**
     * 即刻同步
     *
     * @param int $space_id
     * @param array $body
     * @param array $options
     * @return void
     */
    public function syncFrom($space_id, $body = null, $options = [])
    {
        $options['interface_type'] = $this->interfaceType;
        $response                  = $this->_huoban->execute('POST', "/table/space/{$space_id}/sync_from", $body, $options);
        return $response;
    }

}
