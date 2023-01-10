<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban\Models;

use Huoban\Contracts\RequestInterface;

class HuobanBasic
{
    public $request;
    public $config;

    public function __construct(RequestInterface $request, $config)
    {
        $this->request = $request;
        $this->config  = $config;
    }
}
