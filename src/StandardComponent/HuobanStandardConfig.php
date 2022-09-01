<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-01-20 16:17:35
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban\StandardComponent;

trait HuobanStandardConfig
{
    public function getStandardConfig()
    {
        return [
            // 应用授权名称
            'name'               => 'huoban',
            // 企业api 授权
            'application_id'     => '',
            'application_secret' => '',
            // 别名服务是否开启
            'alias_model'        => true,
            // 应用类型，enterprise，table，share
            'app_type'           => 'enterprise',
            // 工作区id
            'space_id'           => '',
            // 二次验证所必须
            'security_auth'      => false,
            'password'           => '',
            // 企业id
            'company_ids'        => [],
            // pass默认地址，切换本地化部署需要修改
            'urls'               => [
                'api'    => 'https://api.huoban.com',
                'upload' => 'https://upload.huoban.com',
                'bi'     => 'https://bi.huoban.com',
            ],
        ];
    }

}
