<?php

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\ServerException;

use Huoban\Models\HuobanTicket;

class Huoban
{
    public static $client;

    public static $config;

    public static $ticket;
    public static $expired = 1209600;
    /**
     * 初始化
     *
     * @param [type] $config
     * @return void
     */
    public static function init($config)
    {
        self::$config = $config;

        self::setClient();
        //如果ticket存在并且不为空 直接启用传入的ticket;
        self::$ticket = (isset($config['ticket']) && !empty($config['ticket'])) ?  $config['ticket'] : HuobanTicket::getTicket($config);
        self::setTicket();
        //是否开启别名模式
        if (isset($config['alias_model']) && isset($config['space_id'])) {
            self::aliasModel($config['space_id']);
        }
    }
    private static function setClient()
    {
        self::$client =  new Client([
            'base_uri' =>  constant("TEST") ? 'https://api-dev.huoban.com' : 'https://api.huoban.com',
            'timeout'  => 5.0,
            'http_errors' => false
        ]);
    }
    private static function setTicket()
    {
        self::$client->config['headers']['X-Huoban-Ticket'] = self::$ticket;
    }
    public static function aliasModel($space_id)
    {
        self::$client->config['headers']['X-Huoban-Return-Alias-Space-Id'] = $space_id;
    }
    public static function switchFile()
    {
        self::$client->config['base_uri'] = constant("TEST") ? 'https://upload.huoban.com' : 'https://upload.huoban.com';
    }
    public static function switchApi()
    {
        self::$client->config['base_uri'] = constant("TEST") ? 'https://api-dev.huoban.com' : 'https://api.huoban.com';
    }
    public static function format($url, $body = [], $options = [])
    {
        $url = self::formatUrl($url, $options);
        $headers = self::formatHeader($options);
        $body = self::formatBody($body, $headers);

        return ['url' => $url, 'headers' => $headers, 'body' => $body];
    }
    public static function formatUrl($url, $options)
    {
        $version = isset($options['version']) ? '/' . $options['version'] : (isset($options['passVersion']) ? '' : '/v2');
        return "$version$url";
    }
    public static function formatHeader($options)
    {
        $headers = $options['headers'] ?? [];
        $headers['content-type'] = $headers['content-type'] ?? 'application/json';

        return $headers;
    }
    public static function formatBody($body, $headers)
    {
        switch ($headers['content-type']) {
            case 'application/json':
                $body = json_encode($body);
                break;
            default:
                break;
        }
        return $body;
    }
    public static function requestJsonSync($request)
    {
        try {
            $response = self::$client->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        return  json_decode($response->getBody(), true);
    }
    public static function requestAsync($request)
    {
        try {
            $response = self::$client->send($request);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        return  json_decode($response->getBody(), true);
    }
    public static function requestJsonPool($requests, $concurrency = 5)
    {

        $success_data = [];
        $error_data = [];

        $pool = new Pool(self::$client, $requests, [
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
