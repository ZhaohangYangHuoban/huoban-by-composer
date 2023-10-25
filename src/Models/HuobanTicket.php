<?php
/*
 * @Author: SanQian
 * @Date: 2021-08-18 11:37:13
 * @LastEditTime: 2021-10-29 10:47:37
 * @LastEditors: SanQian
 * @Description:
 * @FilePath: /huoban_tools_php/src/Models/HuobanTicket.php
 *
 */

namespace Huoban\Models;

use Huoban\Models\HuobanBasic;

class HuobanTicket extends HuobanBasic
{
    public $expired = 1209600;

    /**
     * 获取企业授权的执行操作
     *
     * @param array  $options
     * @return string|null
     */
    public function getForEnterprise() : string|null
    {
        $body = [ 
            'application_id'     => $this->request->getConfig( 'application_id' ),
            'application_secret' => $this->request->getConfig( 'application_secret' ),
            'expired'            => $this->expired,
        ];

        $response = $this->request->execute( 'POST', '/v2/ticket', $body );

        return $response['ticket'] ?? null;
    }

    /**
     * 获取BI授权的执行操作
     *
     * @param array  $options
     * @return string|null
     */
    public function getForBI() : string|null
    {
        $body = [ 
            'application_id'     => $this->request->getConfig( 'application_id' ),
            'application_secret' => $this->request->getConfig( 'application_secret' ),
            'expired'            => $this->expired,
        ];

        $response = $this->request->execute( 'POST', '/v2/space/app/ticket', $body );

        return $response['ticket'] ?? null;
    }

    /**
     * 获取分享授权的执行操作
     *
     * @param string $share_id
     * @param string $secret
     * @param array  $options
     * @return string|null
     */
    protected function getForShare() : string|null
    {

        $body = [ 
            'share_id' => $this->request->getConfig( 'share_id' ),
            'secret'   => $this->request->getConfig( 'secret' ),
            'expired'  => $this->expired,
        ];

        $response = $this->request->execute( 'POST', '/v2/ticket', $body );

        return $response['ticket'] ?? null;
    }

    /**
     * 获取对应ticket
     *
     * @return string|null
     */
    public function getTicket() : string|null
    {
        $type = $this->request->getConfig( 'app_type' );

        switch ($type) {
            case 'enterprise':
                return $this->getForEnterprise();
            case 'bi':
                return $this->getForBi();
            case 'share':
                return $this->getForShare();
            case 'table':
                return $this->request->getConfig( 'ticket' );
            default:
                return null;
        }
    }

    /**
     * 解析ticket
     *
     * @param array $body
     * @param array $options
     * @return array
     */
    public function parse( array $body = [], array $options = [] ) : array
    {
        return $this->request->execute( 'GET', "/ticket/parse", $body, $options );
    }
}