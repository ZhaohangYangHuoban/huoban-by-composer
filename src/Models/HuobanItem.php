<?php
namespace Huoban\Models;
use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanItem {
    public static function find($table, $body =null,$options =[]) {
        $url = "/item/table/{$table}/find";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function findExc($table, $body =null, $options =[]) {
        $request = self::find($table, $body, $options);
        $response = Huoban::requestJsonSync($request);

        if ($response['filtered'] >= 1) {
            $response['items'] = self::handleItems($response['items']);
        }
        return $response;
    }
    public static function findFirst($table, $body =null, $options =[]) {
        $url = "/item/table/{$table}/find";

        $body = $body ?:[];
        $body['offset'] = 0;
        $body['limit']  = 1;

        $format_data = Huoban::format($url,$body,$options);
        return new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function findFirstExc($table, $body =null, $options =[]) {
        $request = self::findFirst($table, $body, $options);
        $response = Huoban::requestJsonSync($request);

        if ($response['filtered'] >= 1) {
            $response['items'] = self::handleItems($response['items']);
        }
        return $response;
    }
    public static function findAll($table, $body =null, $options =[]) {

        $fir_response = self::findFirstExc($table, $body, $options);

        $url = "/item/table/{$table}/find";
        $body = $body ?:[];
        $body['limit'] = $body['limit']??500;

        $requests = [];
        for ($i = 0; $i < ceil($fir_response['filtered']/$body['limit']); $i++) {

            $body['offset'] = $body['limit'] * $i;

            $format_data = Huoban::format($url,$body,$options);
            $requests[] = new Request('POST',$format_data['url'] ,$format_data['headers'],$format_data['body']);
        }
        return $requests;
    }
    public static function findAllExc($table, $body =null, $options =[]) {
        $success_responses=[];
        $err_responses=[];

        $requests = self::findAll($table, $body, $options);
        Huoban::requestJsonPool($requests,$success_responses,$err_responses);

        $data = ['total'=>'','filtered'=>'','items'=>[]];
        $format_items = [];
        foreach ($success_responses as $success_response) {
            foreach ($success_response['response']['items'] as $item) {
                // $item_id = (string)$item['item_id'];
                $format_items[] = self::returnDiy($item);
            }
            $data['total'] = $success_response['response']['total'];
            $data['filtered'] = $success_response['response']['filtered'];
        }
        $data['items'] = $format_items;  
        return $data;
    }

    public static function update($item_id, $body =null, $options =[]) {
        $url = "/item/{$item_id}";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('put',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function updateExc($item_id, $body =null, $options =[]) {
        $request = self::update($item_id, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return self::returnDiy($response);
    }
    public static function updates($table, $body =null, $options =[]) {
        $url ="/item/table/{$table}/update";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('post',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function updatesExc($table, $body =null, $options =[]) {
        $request = self::updates($table, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return $response;
    }

    public static function create($table, $body =null, $options =[]) {
        $url = "/item/table/{$table}";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('post',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function createExc($table, $body =null, $options =[]) {
        $request = self::create($table, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return self::returnDiy($response);
    }
    public static function creates($table, $body =null, $options =[]) {
        $url = "/item/table/{$table}/create";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('post',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function createsExc($table, $body =null, $options =[]) {
        $request = self::creates($table, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return $response;
    }

    public static function del($item_id, $body =null, $options =[]) {
        $url = "/item/{$item_id}";
        
        $format_data = Huoban::format($url,$body,$options);
        return new Request('delete',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function delExc($item_id, $body =null, $options =[]) {
        $request = self::del($item_id, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return $response;
    }
    public static function dels($table, $body =null, $options =[]) {
        $url = "/item/table/{$table}/delete";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('post',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function delsExc($table, $body =null, $options =[]) {
        $request = self::dels($table, $body, $options);
        $response = Huoban::requestJsonSync($request);
        return $response;
    }

    
    public static function get($item_id, $body =null, $options =[]) {
        $url = "/item/{$item_id}";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('get',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function getExc($item_id, $body =null, $options =[]) {
        $request = self::get($item_id, $body, $options);
        $response = Huoban::requestJsonSync($request);

        return self::returnDiy($response);
    }

    // 暂未启用
    public static function relation($field_id, $body =null, $options =[]) {
        $url = "/item/field/{$field_id}/search";

        $format_data = Huoban::format($url,$body,$options);
        return new Request('post',$format_data['url'] ,$format_data['headers'],$format_data['body']);
    }
    public static function relationExc($field_id, $body =null, $options =[]) {
        $request = self::relation($field_id, $body, $options);
        $response = Huoban::requestJsonSync($request);

        if ($response['filtered'] >= 1) {
            $response['items'] = self::handleItems($response['items']);
        }
        return self::handleItems($response);
    }

    public static function handleItems($items) {
        $format_items = [];
        foreach ($items as $index => $item) {
            $item_id = (string)$item['item_id'];
            $format_items[$item_id] = self::returnDiy($item);
        }
        return $format_items;
    }
    public static function returnDiy($item)
    {
        $format_item= [];
        foreach ($item['fields'] as $field) {
            $field_key = $field['alias']?:(string)$field['field_id'];
            switch ($field['type']) {
                case 'number':
                case 'text':
                case 'calculation':
                case 'date':
                    $format_item[$field_key] = $field['values'][0]['value'];
                    break;
                case 'user':
                    $format_item[$field_key] = $field['values'][0]['name'];
                    $format_item[$field_key . '_uid'] = $field['values'][0]['user_id'];
                    break;
                case 'relation':
                    $ids = [];
                    $titles = [];
                    foreach($field['values'] as $value) {
                        $ids[] = $value['item_id'];
                        $titles[] = $value['title'];
                    }
                    $format_item[$field_key] = implode(',',$titles);
                    $format_item[$field_key.'_ids'] = $ids;
                    $format_item[$field_key.'_titles'] = $titles;
                    break;
                case 'category':
                    $ids = [];
                    $names = [];
                    foreach($field['values'] as $value) {
                        $ids[] = $value['id'];
                        $names[] = $value['name'];
                    }
                    $format_item[$field_key] = implode(',',$names);
                    $format_item[$field_key.'_ids'] = $ids;
                    $format_item[$field_key.'_names'] = $names;
                    break;
                case 'image':
                    $sources = [];
                    foreach($field['values']['link'] as $value) {
                        $sources[] = $value['source'];
                    }
                    $format_item[$field_key] = implode(';',$sources);
                    $format_item[$field_key.'_linksource'] = $sources;

                    $names = [];
                    $fileids = [];
                    foreach($field['values'] as $value) {
                        $names[] = $value['name'];
                        $fileids[] = $value['file_id'];
                    }
                    $format_item[$field_key.'_file_ids'] = $fileids;
                    $format_item[$field_key.'_names'] = $names;
                    break;
                case 'signature':
                    $user = $field['values'][0]['user'];
                    $file = $field['values'][0]['file'];
                    $format_item[$field_key] = $file['link']['source'];
                    $format_item[$field_key. '_user'] = $user;
                    break;
                default:
                    break;
            }
        }
        $format_item['item_id'] = $item['item_id'];
        return $format_item;
    }
}


