<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;

class HuobanTicket
{
    public $_huoban;
    public function __construct($huoban)
    {
        $this->_huoban = $huoban;
    }
    public function getForEnterpriseRequest($application_id, $application_secret, $expired)
    {
        $attr = [
            'application_id'     => $application_id,
            'application_secret' => $application_secret,
            'expired'            => $expired,
        ];
        return new Request('POST', '/v2/ticket', [], json_encode($attr));
    }
    public function getForEnterprise($application_id, $application_secret, $options = [])
    {
        $ticket_name = $this->_huoban->config['name'] . '_enterprise_ticket';
        $expired     = $options['expired'] ?? 1209600;

        $ticket = $this->_huoban->catch->remember($ticket_name, $expired - 3600, function () use ($application_id, $application_secret, $expired) {
            $request  = $this->getForEnterpriseRequest($application_id, $application_secret, $expired);
            $response = $this->_huoban->requestJsonSync($request);
            return $response['ticket'];
        });

        return $ticket;
    }

    public function getForShareRequest($share_id, $secret, $expired)
    {
        $attr = [
            'share_id' => $share_id,
            'secret'   => $secret,
            'expired'  => $expired,
        ];
        return new Request('POST', '/v2/ticket', [], json_encode($attr));
    }
    public function getForShare($share_id, $secret, $options)
    {
        $ticket_name = $this->_huoban->config['name'] . '_share_ticket';
        $expired     = $options['expired'] ?? 1209600;

        $ticket = $this->_huoban->catch->remember($ticket_name, $expired - 3600, function () use ($share_id, $secret, $expired) {
            $request  = $this->getForShareRequest($share_id, $secret, $expired);
            $response = $this->_huoban->requestJsonSync($request);
            return $response['ticket'];
        });

        return $ticket;
    }

    public function getForTable()
    {
        return $_GET['ticket'];
    }

    public function getTicket($config, $options = [])
    {
        $app_type = $config['app_type'] ?? 'table';
        switch ($app_type) {
            case 'table':
                $ticket = $this->getForTable();
                break;
            case 'enterprise':
                $ticket = $this->getForEnterprise($config['application_id'], $config['application_secret'], $options);
                break;
            case 'share':
                $ticket = $this->getForShare($config['share_id'], $config['secret'], $options);
                break;
            default:
                break;
        }
        return $ticket;
    }

    public function parse($body = [], $options = [])
    {
        return $this->_huoban->execute('GET', "/ticket/parse", $body, $options);
    }
}
