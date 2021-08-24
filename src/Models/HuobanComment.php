<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanComment
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    public function createRequest($item_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('POST', "/comment/item/{$item_id}", $body, $options);
    }
    public function create($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/comment/item/{$item_id}", $body, $options);
    }

    public function deleteRequest($comment_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('DELETE', "/comment/{$comment_id}", $body, $options);
    }
    public function delete($comment_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('DELETE', "/comment/{$comment_id}", $body, $options);
    }

    public function getAllRequest($item_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('GET', "/comments/item/{$item_id}", $body, $options);
    }
    public function getAll($item_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/comments/item/{$item_id}", $body, $options);
    }

}
