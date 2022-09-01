<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanOrder extends HuobanBasic
{

    /**
     * 支付
     *
     * @param array $body
     * @param array $options
     * @return void
     */
    public function createRequest($body = [], $options = [])
    {
        return $this->request->getRequest('POST', "/pay_order", $body, $options);
    }
    public function create($body = [], $options = [])
    {
        return $this->request->execute('POST', "/pay_order", $body, $options);
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
        return $this->request->getRequest('GET', "/pay_order/{$order_no}", $body, $options);
    }
    public function order($order_no, $body = [], $options = [])
    {
        return $this->request->execute('GET', "/pay_order/{$order_no}", $body, $options);
    }

}
