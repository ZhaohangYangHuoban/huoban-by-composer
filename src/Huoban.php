<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban;

use Huoban\Request\GuzzleRequest;
use Huoban\Models\HuobanTicket;

class Huoban
{
    use \Huoban\StandardComponent\Config;
    use \Huoban\StandardComponent\HuobanStandardConfig;

    public $request;
    public function __construct( $config = [] )
    {
        $this->config  = $config + $this->getStandardConfig();
        $this->request = new GuzzleRequest( $this->config );

        $this->setTicket();
    }

    public function getTicket()
    {
        $ticket = $this->getConfig( 'ticket' );
        if ( ! $ticket ) {
            $huoban_ticket = new HuobanTicket( $this->request, $this->config );
            $ticket        = $huoban_ticket->getTicket();
        }
        return $ticket;
    }

    public function setTicket()
    {
        $ticket = $this->getTicket();
        $this->setConfig( 'ticket', $ticket );
        $this->request->setConfig( 'ticket', $ticket );
    }
}