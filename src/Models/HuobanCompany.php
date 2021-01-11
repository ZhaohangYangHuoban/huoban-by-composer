<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanCompany
{
    public static function getMemberAll($company_id = null, $body = [], $options = [])
    {
        $requests = [];
        $company_id = $company_id ?: Huoban::$config['company_id'];
        $fir_response = self::getMemberFirst($company_id, $body, $options);
        $body['limit'] = $body['limit'] ?? 500;
        for ($i = 0; $i < ceil($fir_response['total'] / $body['limit']); $i++) {
            $body['offset'] = $body['limit'] * $i;
            $requests[] =   self::getMember($company_id, $body, ['res_type' => 'request']);
        }
        $responses = Huoban::requestJsonPool($requests);
        extract($responses);
        foreach ($success_data as $success_response) {
            foreach ($success_response['response']['members'] as $member) {
                $format_items[] = $member;
            }
            $data['total'] = $success_response['response']['total'];
            $data['joined_total'] = $success_response['response']['joined_total'];
            $data['unactive_total'] = $success_response['response']['unactive_total'];
        }
        $data['members'] = $format_items;
        return $data;
    }
    public static function getMemberFirst($company_id, $body = [], $options = [])
    {
        $body = [
            'limit' => 500,
            'offset' => 0
        ];
        return self::getMember($company_id, $body, $options);
    }
    public static function getMember($company_id, $body = [], $options = [])
    {
        $url = "/company_members/company/{$company_id}";
        $format_data = Huoban::format($url, $body, $options);
        $request = new Request('POST', $format_data['url'], $format_data['headers'], $format_data['body']);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return  $request;
        }
        return Huoban::requestJsonSync($request);
    }
}
