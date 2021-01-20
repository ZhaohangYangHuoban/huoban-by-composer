<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanToken
{
    public static function getToken($config = [], $options = [])
    {
        $request = new Request('POST', '/v2/user/security_auth', [
            'X-Huoban-Ticket' => $config['ticket'],
        ], json_encode([
            'company_ids' => $config['company_ids'],
            'password' => $config['password'],
        ]));
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        $response = Huoban::requestJsonSync($request);
        return $response[0]['token'];
    }
}
