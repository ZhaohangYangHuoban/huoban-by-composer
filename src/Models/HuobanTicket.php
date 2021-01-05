<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanTicket
{
    public static function getForEnterprise($application_id, $application_secret, $options)
    {

        $url = "/ticket";
        $body = [
            'application_id'     => $application_id,
            'application_secret' => $application_secret,
            'expired'            => $options['expired'] ?? 1209600,
        ];

        $format_data = Huoban::format($url, $body, $options);

        $request = new Request('POST', $format_data['url'], $format_data['headers'], $format_data['body']);

        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        $response = Huoban::requestJsonSync($request);

        return $response['ticket'];
    }

    public static function getForShare($share_id, $secret, $options)
    {
        $url = "/ticket";
        $body = [
            'share_id'     => $share_id,
            'secret' => $secret,
            'expired'            => $options['expired'] ?? 1209600,
        ];

        $format_data = Huoban::format($url, $body, $options);

        $request = new Request('POST', $format_data['url'], $format_data['headers'], $format_data['body']);

        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
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
        $app_type = $config['app_type'] ?? null;


        if ($app_type == 'table') {
            $ticket = self::getForTable();
        } elseif ($app_type == 'enterprise') {
            $options['res_type'] = 'response';

            $application_id = $config['application_id'] ?? null;
            $application_secret = $config['application_secret'] ?? null;

            $ticket = self::getForEnterprise($application_id, $application_secret, $options);
        } elseif ($app_type == 'share') {
            $options['res_type'] = 'response';

            $share_id = $config['share_id'] ?? null;
            $secret = $config['secret'] ?? null;

            $ticket = self::getForShare($share_id, $secret, $options);
        } else {
            $ticket = '';
        }

        return $ticket;
    }
}
