<?php
/*
 * @Author: SanQian
 * @Date: 2021-08-18 11:37:13
 * @LastEditTime: 2021-10-25 14:24:46
 * @LastEditors: SanQian
 * @Description:
 * @FilePath: /huoban_leiyu_php/src/Huoban/Models/HuobanTicket.php
 *
 */

namespace Huoban\Models;

use Exception;
use GuzzleHttp\Psr7\Request;
use Huoban\Contracts\RequestInterface;
use Psr\SimpleCache\InvalidArgumentException;

class HuobanTicket
{
    public $request;
    /**
     * @throws \Exception
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * 获取企业授权的请求
     *
     * @param [type] $application_id
     * @param [type] $application_secret
     * @param [type] $expired
     * @return Request
     */
    public function getForEnterpriseRequest($application_id, $application_secret, $expired): Request
    {
        $attr = [
            'application_id'     => $application_id,
            'application_secret' => $application_secret,
            'expired'            => $expired,
        ];

        return new Request('POST', '/v2/ticket', [], json_encode($attr));
    }

    /**
     * 获取企业授权的执行操作
     *
     * @param $application_id
     * @param $application_secret
     * @param array $options
     * @return string
     * @throws Exception
     */
    public function getForEnterprise($application_id, $application_secret, array $options = []): string
    {
        $expired  = $options['expired'] ?? 1209600;
        $request  = $this->getForEnterpriseRequest($application_id, $application_secret, $expired);
        $response = $this->request->requestJsonSync($request);

        return $response['ticket'];
    }

    /**
     * 获取分享授权的请求
     *
     * @param [type] $share_id
     * @param [type] $secret
     * @param [type] $expired
     * @return Request
     */
    protected function getForShareRequest($share_id, $secret, $expired): Request
    {
        $attr = [
            'share_id' => $share_id,
            'secret'   => $secret,
            'expired'  => $expired,
        ];

        return new Request('POST', '/v2/ticket', [], json_encode($attr));
    }

    /**
     * @throws Exception
     */
    protected function getForShare($share_id, $secret, $options)
    {
        $expired  = $options['expired'] ?? 1209600;
        $request  = $this->getForShareRequest($share_id, $secret, $expired);
        $response = $this->request->requestJsonSync($request);

        return $response['ticket'];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getTicket($config, $options = [])
    {
        $type = $config['app_type'];

        switch ($type) {
            case 'enterprise':
                return $this->getForEnterprise($config['application_id'], $config['application_secret'], $options);
            case 'share':
                return $this->getForShare($config['share_id'], $config['secret'], $options);
            case 'table':
                return $config['ticket'];
            default:
                return '';
        }
    }

    public function parse($body = [], $options = [])
    {
        return $this->request->execute('GET', "/ticket/parse", $body, $options);
    }
}
