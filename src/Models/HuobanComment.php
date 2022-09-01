<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanComment extends HuobanBasic
{

    public function createRequest($item_id, $body = [], $options = [])
    {
        return $this->request->getRequest('POST', "/comment/item/{$item_id}", $body, $options);
    }
    public function create($item_id, $body = [], $options = [])
    {
        return $this->request->execute('POST', "/comment/item/{$item_id}", $body, $options);
    }

    public function deleteRequest($comment_id, $body = [], $options = [])
    {
        return $this->request->getRequest('DELETE', "/comment/{$comment_id}", $body, $options);
    }
    public function delete($comment_id, $body = [], $options = [])
    {
        return $this->request->execute('DELETE', "/comment/{$comment_id}", $body, $options);
    }

    public function getAllRequest($item_id, $body = [], $options = [])
    {
        return $this->request->getRequest('GET', "/comments/item/{$item_id}", $body, $options);
    }
    public function getAll($item_id, $body = [], $options = [])
    {
        return $this->request->execute('GET', "/comments/item/{$item_id}", $body, $options);
    }

}
