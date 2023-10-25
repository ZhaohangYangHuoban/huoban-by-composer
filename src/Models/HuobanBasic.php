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
    public RequestInterface $request;

    public function __construct( RequestInterface $request )
    {
        $this->request = $request;
    }
}
