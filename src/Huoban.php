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

    public $request;
    protected $models = [];

    protected $config;

    public function __construct($config = [])
    {
        $this->config = $config + [
            'name'               => 'huoban',
            'alias_model'        => true,
            'app_type'           => 'enterprise',
            'space_id'           => '',
            'application_id'     => '',
            'application_secret' => '',
            // pass默认地址，切换本地化部署需要修改
            'urls'               => [
                'api'    => 'https://api.huoban.com',
                'upload' => 'https://upload.huoban.com',
                'bi'     => 'https://bi.huoban.com',
            ],
        ];
        $this->setRequest();
        $this->setTicket();
    }

    public function setRequest()
    {
        $this->request = new GuzzleRequest($this->config);
    }

    public function setTicket()
    {
        $ticket                          = $this->config['ticket'] ?? $this->make('ticket')->getTicket();
        $this->request->config['ticket'] = $this->config['ticket'] = $ticket;

        return $ticket;
    }

    public function make($model_name)
    {
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

    public function setConfig($key, $val)
    {
        $this->config[$key] = $val;
    }

    public function getConfig($key, $val = '')
    {
        return $this->config[$key] ?? $val;
    }

}
