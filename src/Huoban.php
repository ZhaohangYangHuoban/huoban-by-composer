<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban;

use Exception;
use Huoban\Contracts\Factory;
use Huoban\Request\GuzzleRequest;

class Huoban implements Factory
{
    use \Huoban\StandardComponent\Config;
    use \Huoban\StandardComponent\HuobanStandardConfig;

    public $request;
    protected $models = [];

    protected $config;

    public function __construct($config = [])
    {
        $this->config  = $config + $this->getStandardConfig();
        $this->request = new GuzzleRequest($this->config);

        $this->setTicket();
    }

    public function getTicket()
    {
        $ticket = $this->make('ticket')->getTicket();
        return $ticket;
    }

    public function setTicket()
    {
        $ticket = $this->getTicket();

        $this->setConfig('ticket', $ticket);
        $this->request->setConfig('ticket', $ticket);
    }

    public function make($model_name, $standard = false)
    {
        // 非标准返回返回新建数据对象，【swoole等常驻内存，避免相互影响】
        if (!$standard) {
            return $this->resolve($model_name);
        }

        if (!isset($this->models[$model_name])) {
            $this->models[$model_name] = $this->resolve($model_name);
        }

        return $this->models[$model_name];

    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function resolve($model_name)
    {
        $model = '\\Huoban\\Models\\Huoban' . ucfirst($model_name);
        return new $model($this->request, $this->config);
    }
}
