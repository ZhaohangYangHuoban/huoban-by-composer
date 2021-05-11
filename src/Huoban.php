<?php

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Huoban\Models\HuobanItem;
use Huoban\Models\HuobanTicket;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Huoban
{
    public $httpClient, $apiClient, $uploadClient, $biClient;
    public $_cache, $_item, $_ticket;
    public $config;

    public function __construct($config)
    {
        $this->config = $config + [
            'name'               => 'huoban_sass',
            'alias_model'        => true,
            'app_type'           => 'enterprise',
            'space_id'           => '',
            'application_id'     => '',
            'application_secret' => '',
            'api_url'            => 'https://api.huoban.com',
            'upload_url'         => 'https://upload.huoban.com',
        ];
    }
    public function execute($method, $url, $body = [], $options = [])
    {
        $request = $this->getRequest($method, $url, $body, $options);
        // 普通接口请求('api')，上传请求('upload')，bi请求('bi')，
        $interface_type = $options['interface_type'] ?? 'api';

        return $this->requestJsonSync($request, $interface_type);
    }
    public function getRequest($method, $url, $body = [], $options = [])
    {
        $url     = $options['version'] ?? '/v2' . $url;
        $body    = json_encode($body);
        $headers = $this->defaultHeader($options);
        return new Request($method, $url, $headers, $body);
    }
    public function defaultHeader($options = [])
    {
        $default_headers = [
            'Content-Type'                   => 'application/json',
            'X-Huoban-Ticket'                => $this->config['ticket'] ?? $this->ticket()->getTicket($this->config),
            'X-Huoban-Return-Alias-Space-Id' => $this->config['space_id'] ?? '',
        ];
        $headers = isset($options['headers']) ? $options['headers'] : [];

        return $headers + $default_headers;
    }
    public function requestJsonSync($request, $interface_type = 'api')
    {
        try {
            $response = $this->getHttpClient($interface_type)->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody(), true);
    }
    public function requestJsonPool($requests, $interface_type = 'api', $concurrency = 20)
    {

        $success_data = $error_data = [];

        $pool = new Pool($this->getHttpClient($interface_type), $requests, [
            'concurrency' => $concurrency,
            'fulfilled'   => function ($response, $index) use (&$success_data) {
                $success_data[] = [
                    'index'    => $index,
                    'response' => json_decode($response->getBody(), true),
                ];
            },
            'rejected'    => function ($response, $index) use (&$error_data) {
                $error_data[] = [
                    'index'    => $index,
                    'response' => $response,
                ];
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
        return ['success_data' => $success_data, 'error_data' => $error_data];
    }
    public function getHttpClient($interface_type)
    {
        if ($interface_type == 'api') {
            $client = $this->getApiClient();
        }
        if ($interface_type == 'upload') {
            $client = $this->getUploadClient();
        }
        if ($interface_type == 'bi') {
            $client = $this->getBiClient();
        }
        return $client;
    }
    public function getApiClient($api_url = null)
    {
        if (!$this->apiClient) {
            // 生成不进行效验,错误不打断返回详细信息的客户端
            $this->apiClient = new Client([
                'base_uri'    => $api_url ?: $this->config['api_url'],
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
            ]);
        }
        return $this->apiClient;
    }
    public function getUploadClient($upload_url = null)
    {
        if (!$this->uploadClient) {
            $this->uploadClient = new Client([
                'base_uri'    => $upload_url ?: $this->config['upload_url'],
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
                'headers'     => $this->defaultHeader(),
            ]);
        }
        return $this->uploadClient;
    }
    public function getBiClient($bi_url = null)
    {
        if (!$this->biClient) {
            $this->biClient = new Client([
                'base_uri'    => $bi_url ?: $this->config['bi_url'],
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
                'headers'     => $this->defaultHeader(),
            ]);
        }
        return $this->biClient;
    }
    public function ticket()
    {
        if (!$this->_ticket) {
            $this->_ticket = new HuobanTicket($this);
        }
        return $this->_ticket;
    }
    public function item()
    {
        if (!$this->_item) {
            $this->_item = new HuobanItem($this);
        }
        return $this->_item;
    }

    public function cache()
    {
        if (!$this->_cache) {
            $this->_cache = new FilesystemAdapter();
        }
        return $this->_cache;
    }

    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}
    // public function item($bi_url = null)
    // {}

}
