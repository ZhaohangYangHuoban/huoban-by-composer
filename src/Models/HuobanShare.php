<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanShare
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }
    public function itemCreate($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/item_share/item/{$item_id}", $body, $options);
    }

    public function itemGet($item_share_id = [], $body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/item_share/{$item_share_id}", $body, $options);
    }

    public function itemUpdate($item_share = [], $options = [])
    {
        $item_share_id = $item_share['item_share_id'];
        $body          = $item_share;
        return $this->_huoban->execute('PUT', "/item_share/{$item_share_id}", $body, $options);
    }

    public function itemOpen($item_share, $options = [])
    {
        $item_share['status'] = 'enable';
        return $this->itemUpdate($item_share, $options);
    }
    public function itemClose($item_share, $options = [])
    {
        $item_share['status'] = 'disable';
        return $this->itemUpdate($item_share, $options);
    }
}
