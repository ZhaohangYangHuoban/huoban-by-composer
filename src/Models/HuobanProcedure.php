<?php

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanProcedure extends HuobanBasic
{

    public function runProcedureRequest($procedure_id, $body = [], $options = [])
    {
        return $this->request->getRequest('POST', "/procedure/{$procedure_id}/run", $body, $options);
    }
    public function runProcedure($procedure_id, $body = [], $options = [])
    {
        return $this->request->execute('POST', "/procedure/{$procedure_id}/run", $body, $options);
    }
}
