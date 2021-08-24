<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanProcedure
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    public function runProcedureRequest($procedure_id, $body = [], $options = [])
    {
        return $this->_huoban->getRequest('POST', "/procedure/{$procedure_id}/run", $body, $options);
    }
    public function runProcedure($procedure_id, $body = [], $options = [])
    {
        return $this->_huoban->execute('POST', "/procedure/{$procedure_id}/run", $body, $options);
    }
}
