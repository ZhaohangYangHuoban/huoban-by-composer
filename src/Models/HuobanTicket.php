<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanTicket
{
    public static function getForEnterprise($application_id, $application_secret, $options)
    {
        $request = new Request('POST', '/v2/ticket', [], json_encode([
            'application_id'     => $application_id,
            'application_secret' => $application_secret,
            'expired'            => $options['expired'] ?? 1209600,
        ]));
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return $request;
        }
        $response = Huoban::requestJsonSync($request);
        return $response['ticket'];
    }

    public static function getForShare($share_id, $secret, $options)
    {
        $request = new Request('POST', '/v2/ticket', [], json_encode([
            'share_id' => $share_id,
            'secret'   => $secret,
            'expired'  => $options['expired'] ?? 1209600,
        ]));
        if (isset($options['res_type'])) {
            return $request;
        }
        $response = Huoban::requestJsonSync($request);
        return $response['ticket'];
    }

    public static function getForTable()
    {
        return $_GET['ticket'];
    }

    public static function getTicket($config, $options = [])
    {
        $app_type = $config['app_type'] ?? 'table';
        switch ($app_type) {
            case 'table':
                $ticket = self::getForTable();
                break;
            case 'enterprise':
                $application_id     = $config['application_id'] ?? '';
                $application_secret = $config['application_secret'] ?? '';
                $ticket             = self::getForEnterprise($application_id, $application_secret, $options);
                break;
            case 'share':
                $share_id = $config['share_id'] ?? '';
                $secret   = $config['secret'] ?? '';
                $ticket   = self::getForShare($share_id, $secret, $options);
                break;
            default:
                break;
        }
        return $ticket;
    }
}
