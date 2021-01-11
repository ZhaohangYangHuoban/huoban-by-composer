<?php

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ServerException;

use Huoban\Models\HuobanTicket;

class Huoban
{
    public static $apiClient, $uploadClient;
    public static $config;
    // public static $ticket;
    // public static $ticketExpired = 1209600;
    // public static $findMax = 500;

    /**
     * 初始化
     *
     * @param [type] $config
     * @return void
     */
    public static function init($config)
    {
        self::$config = $config;
    }

    public static function getApiUrl()
    {
        return defined('TEST') && constant('TEST') == true ? 'https://api-dev.huoban.com' : 'https://api.huoban.com';
    }
    private static function getApiClient()
    {
        if (!self::$apiClient) {
            self::$apiClient = new Client([
                'base_uri' =>  self::getApiUrl(),
                'timeout'  => 5.0,
                'verify' => false,
                'http_errors' => false
            ]);
        }
        // 生成不进行效验,错误不打断返回详细信息的客户端
        return self::$apiClient;
    }
    public static function defaultHeader($headers = [])
    {
        $default_headers = [
            'Content-Type' => 'application/json',
            'X-Huoban-Ticket' => self::$config['ticket'] ?? HuobanTicket::getTicket(self::$config),
            'X-Huoban-Return-Alias-Space-Id' => self::$config['space_id'] ?? '',
        ];
        return $headers +  $default_headers;
    }
    public static function requestJsonSync($request)
    {
        try {
            $response = self::getApiClient()->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        return  json_decode($response->getBody(), true);
    }
    public static function getRequest($method, $url, $body = [], $options = [])
    {
        $url = $options['version'] ?? '/v2' . $url;
        $body = json_encode($body);
        $headers = Huoban::defaultHeader();
        return new Request($method, $url, $headers, $body);
    }
    public static function requestJsonPool($requests, $concurrency = 100)
    {

        $success_data = [];
        $error_data = [];
        $pool = new Pool(self::getApiClient(), $requests, [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use (&$success_data) {
                $success_data[] = [
                    'index' => $index,
                    'response' => json_decode($response->getBody(), true),
                ];
            },
            'rejected' => function ($response, $index) use (&$error_data) {
                $error_data[] = [
                    'index' => $index,
                    'response' => $response,
                ];
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return ['success_data' => $success_data, 'error_data' => $error_data];
    }
    /**
     * 临时切换权限
     *
     * @param [type] $tmp_config
     * @return void
     */
    public static function switchTmpAuth($tmp_config)
    {
        self::init($tmp_config);
    }
    /**
     * 临时原有权限
     *
     * @param [type] $tmp_config
     * @return void
     */
    public static function switchFormerAuth()
    {
        self::init(self::$config);
    }
}
