<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanStorage extends HuobanBasic
{

    public function getStorageRequest($body = [], $options = [])
    {
        return $this->request->getRequest('GET', "/storage", $body, $options);
    }

    public function getStorage($body = [], $options = [])
    {
        return $this->request->execute('GET', "/storage", $body, $options);
    }

    public function setStorageRequest($body = [], $options = [])
    {
        return $this->request->getRequest('POST', "/storage", $body, $options);
    }
    public function setStorage($body = [], $options = [])
    {
        return $this->request->execute('POST', "/storage", $body, $options);
    }
}
