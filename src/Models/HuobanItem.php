<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanItem
{
    public static function find($table, $body = [], $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/table/{$table}/find", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        $response = Huoban::requestJsonSync($request);
        if ($response['filtered'] >= 1) {
            $response['items'] = self::handleItems($response['items']);
        }
        return $response;
    }
    public static function findAll($table, $body = [], $options = [])
    {
        $requests = [];
        $responses = [];
        // 单次查询最高500条
        $body['limit'] = 500;
        $fir_response = self::find($table, $body, $options + ['res_type' => 'response']);
        // 查询全部数据的所有请求
        for ($i = 0; $i < ceil($fir_response['filtered'] / $body['limit']); $i++) {
            $body['offset'] = $body['limit'] * $i;
            $requests[] = Huoban::getRequest('POST', "/item/table/{$table}/find", $body, $options);
        }
        // 如果获取的是请求
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $requests;
        }
        // 如果查询结果不足500，直接返回结果集
        if ($fir_response['filtered'] < $body['limit']) {
            return $fir_response;
        }
        // 如果查询结果超过500，返回结果集并格式化批处理结果
        $responses = Huoban::requestJsonPool($requests);
        $format_items = [];
        foreach ($responses['success_data'] as $success_response) {
            $format_items =  $format_items + self::handleItems($success_response['response']['items']);
        }
        return [
            'total' => $fir_response['total'],
            'filtered' => $fir_response['filtered'],
            'items' => $format_items,
        ];
    }
    public static function update($item_id, $body = [], $options = [])
    {
        $request = Huoban::getRequest('PUT', "/item/{$item_id}", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        $response = Huoban::requestJsonSync($request);
        return self::returnDiy($response);
    }
    public static function updates($table, $body = [], $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/table/{$table}/update", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function create($table, $body = null, $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/table/{$table}", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return self::returnDiy(Huoban::requestJsonSync($request));
    }
    public static function creates($table, $body = null, $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/table/{$table}/create", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function del($item_id, $body = null, $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/{$item_id}", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function dels($table, $body = null, $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/table/{$table}/delete", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function get($item_id, $body = null, $options = [])
    {
        $request = Huoban::getRequest('POST', "/item/{$item_id}", $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function handleItems($items)
    {
        $format_items = [];
        foreach ($items as $index => $item) {
            $item_id = (string)$item['item_id'];
            $format_items[$item_id] = self::returnDiy($item);
        }
        return $format_items;
    }
    public static function returnDiy($item)
    {
        $format_item = [];
        foreach ($item['fields'] as $field) {
            $field_key = $field['alias'] ?: (string)$field['field_id'];
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
                    foreach ($field['values'] as $value) {
                        $ids[] = $value['item_id'];
                        $titles[] = $value['title'];
                    }
                    $format_item[$field_key] = implode(',', $titles);
                    $format_item[$field_key . '_ids'] = $ids;
                    $format_item[$field_key . '_titles'] = $titles;
                    break;
                case 'category':
                    $ids = [];
                    $names = [];
                    foreach ($field['values'] as $value) {
                        $ids[] = $value['id'];
                        $names[] = $value['name'];
                    }
                    $format_item[$field_key] = implode(',', $names);
                    $format_item[$field_key . '_ids'] = $ids;
                    $format_item[$field_key . '_names'] = $names;
                    break;
                case 'image':
                    $sources = [];
                    foreach ($field['values']['link'] as $value) {
                        $sources[] = $value['source'];
                    }
                    $format_item[$field_key] = implode(';', $sources);
                    $format_item[$field_key . '_linksource'] = $sources;

                    $names = [];
                    $fileids = [];
                    foreach ($field['values'] as $value) {
                        $names[] = $value['name'];
                        $fileids[] = $value['file_id'];
                    }
                    $format_item[$field_key . '_file_ids'] = $fileids;
                    $format_item[$field_key . '_names'] = $names;
                    break;
                case 'signature':
                    $user = $field['values'][0]['user'];
                    $file = $field['values'][0]['file'];
                    $format_item[$field_key] = $file['link']['source'];
                    $format_item[$field_key . '_user'] = $user;
                    break;
                default:
                    break;
            }
        }
        $format_item['item_id'] = $item['item_id'];
        return $format_item;
    }
}
