<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanFollow
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }
    public function create($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/follow/item/{$item_id}", $body, $options);
    }
    public function delete($ref_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/follow/item/{$ref_id}", $body, $options);
    }
    public function getAll($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/follow/item/{$item_id}/find", $body, $options);
    }
}
