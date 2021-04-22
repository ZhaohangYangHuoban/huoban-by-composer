<?php

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Huoban\Models\HuobanTicket;

class Huoban
{
    public static $httpClient, $apiClient, $uploadClient, $biClient;
    public static $config;
    public static function init($config)
    {
        self::$config = $config;
        self::setHttpClient();
    }
    public static function execute($method, $url, $body = [], $options = [])
    {
        $request = Huoban::getRequest($method, $url, $body, $options);
        if (isset($options['res_type']) && $options['res_type'] == 'request') {
            return $request;
        }
        return Huoban::requestJsonSync($request);
    }
    public static function getRequest($method, $url, $body = [], $options = [])
    {
        $url     = $options['version'] ?? '/v2' . $url;
        $body    = json_encode($body);
        $headers = Huoban::defaultHeader($options);
        return new Request($method, $url, $headers, $body);
    }
    public static function defaultHeader($options = [])
    {
        self::$config['ticket'] = self::$config['ticket'] ?? HuobanTicket::getTicket(self::$config);

        $default_headers = [
            'Content-Type'                   => 'application/json',
            'X-Huoban-Ticket'                => self::$config['ticket'],
            'X-Huoban-Return-Alias-Space-Id' => self::$config['space_id'] ?? '',
        ];
        $headers = isset($options['headers']) ? $options['headers'] : [];

        return $headers + $default_headers;
    }
    public static function requestJsonSync($request)
    {
        try {
            $response = self::getHttpClient()->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody(), true);
    }
    public static function requestJsonPool($requests, $concurrency = 100)
    {

        $success_data = [];
        $error_data   = [];
        $pool         = new Pool(self::getHttpClient(), $requests, [
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
    public static function setHttpClient($type = 'api')
    {
        if ($type == 'api') {
            self::$httpClient = self::getApiClient();
        }
        if ($type == 'upload') {
            self::$httpClient = self::getUploadClient();
        }
        if ($type == 'bi') {
            self::$httpClient = self::getBiClient();
        }
    }
    public static function getHttpClient()
    {
        return self::$httpClient;
    }
    public static function getApiClient()
    {
        if (!self::$apiClient) {
            // 生成不进行效验,错误不打断返回详细信息的客户端
            self::$apiClient = new Client([
                'base_uri'    => defined('TEST') && constant('TEST') == true ? 'https://api.huoban.com' : 'https://api.huoban.com',
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
            ]);
        }
        return self::$apiClient;
    }
    public static function getUploadClient()
    {
        if (!self::$uploadClient) {
            self::$uploadClient = new Client([
                'base_uri'    => defined('TEST') && constant('TEST') == true ? 'https://upload.huoban.com' : 'https://upload.huoban.com',
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
                'headers'     => self::defaultHeader(),
            ]);
        }
        return self::$uploadClient;
    }
    public static function getBiClient()
    {
        if (!self::$biClient) {
            self::$biClient = new Client([
                'base_uri'    => defined('TEST') && constant('TEST') == true ? 'https://bi.huoban.com' : 'https://bi.huoban.com',
                'timeout'     => 600,
                'verify'      => false,
                'http_errors' => false,
                'headers'     => self::defaultHeader(),
            ]);
        }
        return self::$biClient;
    }
}
