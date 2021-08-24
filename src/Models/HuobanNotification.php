<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanNotification
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    public function createRequest($body = [], $options = [])
    {
        return $this->_huoban->getRequest('POST', "/notification", $body, $options);
    }
    public function create($body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/notification", $body, $options);
    }
}
