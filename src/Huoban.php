<?php

namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\ServerException;

use Huoban\Models\HuobanTicket;

class Huoban
{
   public static $client;

   public static $appType;
   public static $applicationId;
   public static $applicationSecret;

   public static $initParams;
   public static $tmpParams;

   public static $ticket;
   public static $expired = 1209600;

   /**
    * 初始化
    *
    * @param [type] $params
    * @return void
    */
   public static function init($params)
   {
      self::$initParams = $params;

      self::$appType = $params['app_type'] ?? 'table';
      self::$applicationId = $params['application_id'] ?? null;
      self::$applicationSecret = $params['application_secret'] ?? null;

      self::setClient();
      //如果ticket存在并且不为空 直接启用传入的ticket;
      $ticket = (isset($params['ticket']) && !empty($params['ticket'])) ?  $params['ticket'] : HuobanTicket::getTicket(self::$appType, self::$applicationId, self::$applicationSecret);
      self::setTicket($ticket);
      //是否开启别名模式
      if (isset($params['alias_model']) && isset($params['space_id'])) {
         self::aliasModel($params['space_id']);
      }
   }
   private static function setClient()
   {
      self::$client =  new Client([
         'base_uri' =>  constant("TEST") ? 'https://api-dev.huoban.com' : 'https://api.huoban.com',
         'timeout'  => 5.0,
      ]);
   }
   private static function setTicket($ticket)
   {
      self::$client->config['headers']['X-Huoban-Ticket'] = $ticket;
   }
   public static function aliasModel($space_id)
   {
      self::$client->config['headers']['X-Huoban-Return-Alias-Space-Id'] = $space_id;
   }
   public static function format($url, $body, $options)
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
    * @param [type] $tmp_params
    * @return void
    */
   public static function switchTmpAuth($tmp_params)
   {
      self::init($tmp_params);
   }
   /**
    * 临时原有权限
    *
    * @param [type] $tmp_params
    * @return void
    */
   public static function switchFormerAuth()
   {
      self::init(self::$initParams);
   }
}
