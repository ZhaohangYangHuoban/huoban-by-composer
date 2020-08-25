<?php
namespace Huoban\Models;
use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanFollow {

    public static function create($item_id) {
        $url = "/follow/item/{$item_id}";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function createExc($item_id) {
        $request = self::create($item_id, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }
    public static function delete($ref_id) {
        $url = "/follow/item/{$ref_id}";
                
        $format_data = Huoban::format($url,$body,$options);
        return new Request('delete',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function deleteExc($ref_id, $body =null, $options =[]) {
        $request = self::delete($ref_id, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }
    public static function getAll($item_id, $attributes = array()) {
        $url = "/follow/item/{$item_id}/find";
                
        $format_data = Huoban::format($url,$body,$options);
        return new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function getAllExc($item_id, $body =null, $options =[]) {
        $request = self::getAll($item_id, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }
}


