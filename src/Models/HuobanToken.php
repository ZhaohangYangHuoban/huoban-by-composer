<?php
/*
 * @Author: SanQian
 * @Date: 2021-09-07 11:39:26
 * @LastEditTime: 2021-09-07 15:37:10
 * @LastEditors: SanQian
 * @Description:
 * @FilePath: /kuaidi100/vendor/zhaohangyang/huoban_tools_php/src/Models/HuobanToken.php
 *
 */

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use Huoban\Huoban;

class HuobanToken
{
    public $_huoban;

    public function __construct($huoban)
    {
        $this->_huoban = $huoban;
    }

    public function getToken($config = [], $options = [])
    {
        $request = new Request('POST', '/v2/user/security_auth', [
            'X-Huoban-Ticket' => $config['ticket'],
        ], json_encode([
            'company_ids' => $config['company_ids'],
            'password'    => $config['password'],
        ]));
        if (isset($options['res_type']) && 'request' == $options['res_type']) {
            return $request;
        }
        $response = $this->_huoban->requestJsonSync($request);
        return $response[0]['token'];
    }

    /**
     * 获取个人授权的Token请求
     *
     * @param [type] $username
     * @param [type] $password
     * @param [type] $expired
     * @return void
     */
    public function getUserTokenRequest($username, $password, $expired, $options)
    {

        $attr = [
            'client_id'     => $options['client_id'] ?? 'YOUR CLINT ID',
            'client_secret' => $options['client_secret'] ?? 'YOUR CLINT SECRET',
            'grant_type'    => $options['grant_type'] ?? 'password',
            'username'      => $username,
            'password'      => $password,
            'expires_in'    => $expired,
        ];

        return new Request('POST', '/v2/auth/token', [], json_encode($attr));
    }
    /**
     * 获取个人授权的Token执行操作
     *
     * @param [type] $username
     * @param [type] $password
     * @param array $options
     * @return void
     */
    public function getUserToken($username, $password, $options = [])
    {
        $token_name = $this->_huoban->config['name'] . '_user_token';
        $expired    = $options['expired'] ?? 1209600;

        $token = $this->_huoban->_cache->remember($token_name, $expired - 3600, function () use ($username, $password, $expired, $options) {
            $request  = $this->getUserTokenRequest($username, $password, $expired, $options);
            $response = $this->_huoban->requestJsonSync($request);

            return 'Bearer ' . $response['access_token'];
        });
        return $token;
    }
}
