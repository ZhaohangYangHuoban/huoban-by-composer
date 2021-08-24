<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanOrder
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    /**
     * 支付
     *
     * @param array $body
     * @param array $options
     * @return void
     */
    public function createRequest($body = [], $options = [])
    {
        return $this->_huoban->getRequest('POST', "/pay_order", $body, $options);
    }
    public function create($body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/pay_order", $body, $options);
    }

    /**
     * 订单状态查询接口
     *
     * @param [type] $order_no
     * @param array $body
     * @param array $options
     * @return void
     */
    public function orderRequest($order_no, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('GET', "/pay_order/{$order_no}", $body, $options);
    }
    public function order($order_no, $body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/pay_order/{$order_no}", $body, $options);
    }

}
