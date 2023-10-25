<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanFollow extends HuobanBasic
{

    public function create($item_id, $body = [], $options = [])
    {
        return $this->request->execute('POST', "/follow/item/{$item_id}", $body, $options);
    }
    public function delete($ref_id, $body = [], $options = [])
    {
        return $this->request->execute('POST', "/follow/item/{$ref_id}", $body, $options);
    }
    public function getAll($item_id, $body = [], $options = [])
    {
        return $this->request->execute('POST', "/follow/item/{$item_id}/find", $body, $options);
    }
}
