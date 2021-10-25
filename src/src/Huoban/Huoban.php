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
use Psr\SimpleCache\InvalidArgumentException;

class Huoban implements Factory
{

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
        $this->config['ticket'] ?? $this->setTicket();
    }

    public function setTicket($ticket = null)
    {
        $this->config['ticket'] = $ticket ?: $this->make('Ticket')->getTicket();
        return $this->config['ticket'];
    }

    public function make($model_name)
    {
        return $this->models[$model_name] = $this->resolve($model_name);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function resolve($model_name)
    {
        $model   = '\\Huoban\\Models\\Huoban' . ucfirst($model_name);
        $request = new GuzzleRequest($this->config);

        return new $model($request);
    }

}
