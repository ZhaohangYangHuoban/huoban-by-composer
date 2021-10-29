<?php

namespace Huoban\Models;

class HuobanNotification extends HuobanBasic
{

    public function createRequest($body = [], $options = [])
    {
        return $this->request->getRequest('POST', "/notification", $body, $options);
    }
    public function create($body = [], $options = [])
    {
        return $this->request->execute('POST', "/notification", $body, $options);
    }
}
