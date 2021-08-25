<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Huoban\Models\HuobanBi;
use Huoban\Models\HuobanBiFile;
use Huoban\Models\HuobanBiTable;
use Huoban\Models\HuobanCache;
use Huoban\Models\HuobanComment;
use Huoban\Models\HuobanCompany;
use Huoban\Models\HuobanItem;
use Huoban\Models\HuobanMembers;
use Huoban\Models\HuobanNotification;
use Huoban\Models\HuobanProcedure;
use Huoban\Models\HuobanShare;
use Huoban\Models\HuobanSpace;
use Huoban\Models\HuobanStream;
use Huoban\Models\HuobanTable;
use Huoban\Models\HuobanTicket;
use Huoban\Models\HuobanToken;
use Huoban\Models\HuobanUser;

class Huoban
{
    /**
     * 当前 请求的客户端
     *
     * @var \GuzzleHttp\Client
     */
    public $httpClient;
    /**
     * api 请求的客户端
     *
     * @var \GuzzleHttp\Client
     */
    public $apiClient;
    /**
     * 文件上传 请求的客户端
     *
     * @var \GuzzleHttp\Client
     */
    public $uploadClient;
    /**
     * BI上传 请求的客户端
     *
     * @var \GuzzleHttp\Client
     */
    public $biClient;
    /**
     * 文件基础配置
     *
     * @var array
     */
    public $config;

    /**
     * 初始化配置信息
     *
     * @param array $config
     */
    public function __construct(array $config)
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
    /**
     * 执行具体操作
     *
     * @param string $method
     * @param string $url
     * @param array $body
     * @param array $options
     * @return void
     */
    public function execute($method, $url, $body = [], $options = [])
    {
        $request = $this->getRequest($method, $url, $body, $options);
        // 普通接口请求('api')，上传请求('upload')，bi请求('bi')，
        $interface_type = $options['interface_type'] ?? 'api';

        return $this->requestJsonSync($request, $interface_type);
    }
    /**
     * 获取执行工作的请求
     *
     * @param string $method
     * @param string $url
     * @param array $body
     * @param array $options
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getRequest($method, $url, $body = [], $options = [])
    {
        $url     = $options['version'] ?? '/v2' . $url;
        $body    = json_encode($body);
        $headers = $this->defaultHeader($options);
        return new Request($method, $url, $headers, $body);
    }
    /**
     * 设置请求的默认请求头
     *
     * @param array $options
     * @return array
     */
    public function defaultHeader($options = [])
    {

        $default_headers = [
            'Content-Type'                   => 'application/json',
            'X-Huoban-Ticket'                => $this->config['ticket'] ?? $this->_ticket->getTicket($this->config),
            'X-Huoban-Return-Alias-Space-Id' => $this->config['space_id'] ?? '',
        ];
        $headers = isset($options['headers']) ? $options['headers'] : [];

        return $headers + $default_headers;
    }
    /**
     * 发送请求，并返回结果
     *
     * @param \GuzzleHttp\Psr7\Request $request
     * @param string $interface_type
     * @return void
     */
    public function requestJsonSync(\GuzzleHttp\Psr7\Request $request, $interface_type = 'api')
    {
        try {
            $response = $this->getHttpClient($interface_type)->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }

        return json_decode($response->getBody(), true);
    }
    /**
     * 批量发送请求，并返回结果
     *
     * @param \GuzzleHttp\Psr7\Request $requests
     * @param string $interface_type
     * @param integer $concurrency
     * @return array
     */
    public function requestJsonPool($requests, $interface_type = 'api', $concurrency = 20)
    {

        $success_data = $error_data = [];
        $client       = $this->getHttpClient($interface_type);

        $pool = new Pool($client, $requests, [
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
    /**
     * 获取请求客户端
     *
     * @param string $interface_type
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient($interface_type)
    {
        if ('api' == $interface_type) {
            $client = $this->getApiClient();
        }
        if ('upload' == $interface_type) {
            $client = $this->getUploadClient();
        }
        if ('bi' == $interface_type) {
            $client = $this->getBiClient();
        }
        return $client;
    }
    /**
     * 获取api请求客户端
     *
     * @param string $api_url
     * @return \GuzzleHttp\Client
     */
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
    /**
     * 获取上传文件请求客户端
     *
     * @param string $upload_url
     * @return \GuzzleHttp\Client
     */
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
    /**
     * 获取BI请求客户端
     *
     * @param string $bi_url
     * @return \GuzzleHttp\Client
     */
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
    /**
     * 按需加载伙伴模块，如果也可以单独加载
     *
     * @param string $class_name
     * @return object
     */
    public function __get($class_name)
    {
        switch ($class_name) {
            case '_ticket':
                $class_obj = $this->_ticket = $this->_ticket ?? new HuobanTicket($this);
                break;
            case '_item':
                $class_obj = $this->_item = $this->_item ?? new HuobanItem($this);
                break;
            case '_table':
                $class_obj = $this->_table = $this->_table ?? new HuobanTable($this);
                break;
            case '_cache':
                $class_obj = $this->_cache = $this->_cache ?? new HuobanCache($this);
                break;
            case '_space':
                $class_obj = $this->_space = $this->_space ?? new HuobanSpace($this);
            case '_procedure':
                $class_obj = $this->_procedure = $this->_procedure ?? new HuobanProcedure($this);
                break;
            case '_comment':
                $class_obj = $this->_comment = $this->_comment ?? new HuobanComment($this);
                break;
            case '_user':
                $class_obj = $this->_user = $this->_user ?? new HuobanUser($this);
                break;
            case '_members':
                $class_obj = $this->_members = $this->_members ?? new HuobanMembers($this);
                break;
            case '_notification':
                $class_obj = $this->_notification = $this->_notification ?? new HuobanNotification($this);
                break;
            case '_token':
                $class_obj = $this->_token = $this->_token ?? new HuobanToken($this);
                break;
            case '_stream':
                $class_obj = $this->_stream = $this->_stream ?? new HuobanStream($this);
                break;
            case '_share':
                $class_obj = $this->_share = $this->_share ?? new HuobanShare($this);
                break;
            case '_company':
                $class_obj = $this->_company = $this->_company ?? new HuobanCompany($this);
                break;
            case '_bi':
                $class_obj = $this->_bi = $this->_bi ?? new HuobanBi($this);
                break;
            case '_biFile':
                $class_obj = $this->_biFile = $this->_biFile ?? new HuobanBiFile($this);
                break;
            case '_biTable':
                $class_obj = $this->_biTable = $this->_biTable ?? new HuobanBiTable($this);
                break;

            default:
                break;
        }
        return $class_obj;
    }
}
