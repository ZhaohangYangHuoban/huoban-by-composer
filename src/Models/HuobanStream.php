<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanStream
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    /**
     * 获取item动态
     * $body = array(
     *     'limit' => 10,
     *     'last_stream_id' => 11001,
     * );
     */
    public function getRequest($item_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('GET', "/streams/item/{$item_id}", $body, $options);
    }
    public function get($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/streams/item/{$item_id}", $body, $options);
    }
}
