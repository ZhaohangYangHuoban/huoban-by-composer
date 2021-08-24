<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanUser
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    /**
     * 获取用户基础信息
     *
     * @return void
     */
    public function getRequest()
    {
        return $this->_huoban->getRequest('POST', "/user");
    }
    public function get()
    {
        return $this->_huoban->execute('POST', "/user");
    }

    public function findRequest($body = [])
    {
        return $this->_huoban->getRequest('POST', "/users/find", $body);
    }
    public function find($body = [])
    {
        return $this->_huoban->execute('POST', "/users/find", $body);
    }
}
