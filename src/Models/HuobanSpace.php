<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanSpace
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }
    public function getSpaceRequest($space_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('GET', "/v2/space/{$space_id}", $body, $options);
    }
    public function getSpace($space_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/v2/space/{$space_id}", $body, $options);
    }
}
