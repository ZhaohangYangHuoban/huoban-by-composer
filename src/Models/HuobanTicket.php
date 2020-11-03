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

    public static function getForTable()
    {
        return $_GET['ticket'];
    }


    public static function getTicket($app_type, $application_id, $application_secret, $options = [])
    {
        if ($app_type == 'table') {
            $ticket = self::getForTable();
        } else {
            $options['res_type'] = 'response';
            $ticket = self::getForEnterprise($application_id, $application_secret, $options);
        }

        return $ticket;
    }
}
