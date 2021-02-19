<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanCompany
{
    public static function getMemberAll($company_id = null, $body = [], $options = [])
    {
        $requests  = [];
        $responses = [];
        // 单次查询最高500条
        $body['limit'] = 500;
        $fir_response  = self::getMember($company_id, $body, $options + ['res_type' => 'response']);
        // 查询全部数据的所有请求
        for ($i = 0; $i < ceil($fir_response['filtered'] / $body['limit']); $i++) {
            $body['offset'] = $body['limit'] * $i;
            $requests[]     = self::getMember($company_id, $body, $options + ['res_type' => 'request']);
        }
        // 如果获取的是请求
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return $requests;
        }
        // 如果查询结果不足500，直接返回结果集
        if ($fir_response['filtered'] < $body['limit']) {
            return $fir_response;
        }
        // 如果查询结果超过500，返回结果集并格式化批处理结果
        $responses = Huoban::requestJsonPool($requests);
        foreach ($responses['success_data'] as $success_response) {
            foreach ($success_response['response']['members'] as $member) {
                $format_items[] = $member;
            }
        }
        return [
            'total'          => $fir_response['total'],
            'joined_total'   => $fir_response['joined_total'],
            'unactive_total' => $fir_response['unactive_total'],
            'members'        => $format_items,
        ];
    }
    public static function getMember($company_id, $body = [], $options = [])
    {
        return Huoban::execute('POST', "/company_members/company/{$company_id}", $body, $options);
    }
}
