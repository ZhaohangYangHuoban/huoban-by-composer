<?php

namespace App\Config;

class HuobanConfig
{
    public static function getHuobanConfig()
    {
        return [
            'app_type' => 'enterprise',
            // 接口api地址
            'api_url' => defined('TEST') && constant('TEST') == true ? 'https://api-dev.huoban.com' : 'https://api.huoban.com',
            'upload_url' => defined('TEST') && constant('TEST') == true ? 'https://upload.huoban.com' : 'https://upload.huoban.com',
            // 企业api
            'application_id' => '',
            'application_secret' => '',
            // 别名
            'alias_model' => true,
            'space_id' => '',
            // 二次验证所必须
            'security_auth' => false,
            'password' => '',
            'company_ids' => [],
        ];
    }
}
