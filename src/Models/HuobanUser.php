<?php

namespace Huoban\Models;

use Huoban\HuobanBasic;

class HuobanUser extends HuobanBasic
{

    /**
     * 获取用户基础信息
     *
     * @return void
     */
    public function getRequest()
    {
        return $this->request->getRequest('GET', "/user");
    }
    public function get()
    {
        return $this->request->execute('GET', "/user");
    }

    public function findRequest($body = [])
    {
        return $this->request->getRequest('POST', "/users/find", $body);
    }
    public function find($body = [])
    {
        return $this->request->execute('POST', "/users/find", $body);
    }
}
