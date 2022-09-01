<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanSpace extends HuobanBasic
{

    public function getSpaceRequest($space_id, $body = [], $options = [])
    {
        return $this->request->getRequest('GET', "/space/{$space_id}", $body, $options);
    }
    public function getSpace($space_id, $body = [], $options = [])
    {
        return $this->request->execute('GET', "/space/{$space_id}", $body, $options);
    }
}
