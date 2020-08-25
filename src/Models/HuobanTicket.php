<?php
namespace Huoban\Models;
use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanTicket {
    public static function getForEnterprise($application_id,$application_secret,$expired = 1209600) {
        $url = "/ticket";
        $body =[
            'application_id'     => $application_id,
            'application_secret' => $application_secret,
            'expired'            => $expired,
        ];
        $options =[];
        
        $format_data = Huoban::format($url,$body,$options);
        return new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }

    public static function getForTable() {
        return $_GET['ticket'];
    }
}
