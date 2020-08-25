<?php
namespace Huoban;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;

use Huoban\Models\HuobanTicket;

class Huoban{
   public static $client;
   
   public static $appType;
   public static $applicationId;
   public static $applicationSecret;

   public static $ticket;
   public static $expired = 1209600;
   
   public static $model = "normal";
   public static $paramsForModel;

   public  static function init($app_type ='table',$application_id = null,$application_secret = null){
      defined('HBVERSION') or define('HBVERSION', "/v2");
      self::$appType = $app_type;
      self::$applicationId = $application_id;
      self::$applicationSecret = $application_secret;
      self::setClient();
      self::setTicket();
   }

   private static function setClient(){
      self::$client =  new Client([
         // Base URI is used with relative requests
         'base_uri' =>  constant("TEST") ? 'https://api-dev.huoban.com' : 'https://api.huoban.com',
         // You can set any number of default request options.
         'timeout'  => 5.0,
     ]);
   }
   private static function setTicket(){
      if (!isset(self::$ticket)) {
         if (self::$appType == 'table') {
            self::$ticket = HuobanTicket::getForTable();
         }else{
            $request = HuobanTicket::getForEnterprise(self::$applicationId,self::$applicationSecret,self::$expired);
            $response = self::requestJsonSync($request);
            self::$ticket = $response['ticket'];
         }
      }
   }
 
   public static function aliasModel($space_id){
      self::$model = 'alias';
      self::$paramsForModel['spaceId'] = $space_id;
   }

   public static function format($url,$body,$options){
      $url = self::formatUrl($url,$options);
      $headers = self::formatHeader($options);
      $body = self::formatBody($body,$headers);

      return ['url'=> $url,'headers'=> $headers,'body'=> $body];
   }
   public static function formatHeader($options){
      $headers = $options['headers']??[];
      $headers['content-type'] = $headers['content-type']?:'application/json';

      if (self::$model ==  'alias') {
         $headers['X-Huoban-Return-Alias-Space-Id'] = self::$paramsForModel['spaceId'];
      }
      isset(self::$ticket) && $headers['X-Huoban-Ticket'] = self::$ticket;

      return $headers;
   }
   public static function formatBody($body,$headers){
      switch ($headers['content-type']) {
         case 'application/json':
            $body = json_encode($body);
         break;
         default:
         break;
      }
      return $body;
   }
   public static function formatUrl($url,$options){
      $version = isset($options['version']) ? '/' . $options['version'] : (isset($options['passVersion']) ? '' : '/v2');
      return "$version$url";
   }

   private static function getDefaltOptions(){
      $options = [
          'timeout'=> 20,
      ];
      return $options;
   }

   public static function requestJsonSync($request)
   {
      $defalt_options = self::getDefaltOptions();
      $response = self::$client->send($request,$defalt_options);
      return  json_decode($response->getBody(),true);
   }
   public static function requestJsonPromise($requests)
   {
   }
   public static function requestJsonPool($requests,&$success_data,&$error_data,$concurrency = 5){

      $pool = new Pool(self::$client,$requests, [
         'concurrency' => $concurrency,
         'fulfilled' => function ($response, $index) use (&$success_data) {
             // this is delivered each successful response
             $success_data[] = [
               'index'=>$index,
               'response'=>json_decode($response->getBody(),true),
             ];
         },
         'rejected' => function ($response, $index) use (&$error_data) {
             // this is delivered each failed request
             $error_data[] = [
               'index'=>$index,
               'response'=>$response,
             ];
         },
      ]);
      // Initiate the transfers and create a promise
      $promise = $pool->promise();
      // Force the pool of requests to complete.
      $promise->wait();
   }
   public static function requestAsync($request)
   {
      $defalt_options = self::getDefaltOptions();
      $response = self::$client->send($request,$defalt_options);
      return  json_decode($response->getBody(),true);
   }
}
