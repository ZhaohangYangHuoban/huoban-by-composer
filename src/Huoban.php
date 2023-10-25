<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban;

use Huoban\Request\GuzzleRequest;
use Huoban\Models\HuobanTicket;
use Huoban\Models\HuobanBasic;
use Huoban\Models\HuobanBi;
use Huoban\Models\HuobanBiFile;
use Huoban\Models\HuobanBiItem;
use Huoban\Models\HuobanBiTable;
use Huoban\Models\HuobanComment;
use Huoban\Models\HuobanCompany;
use Huoban\Models\HuobanFile;
use Huoban\Models\HuobanFollow;
use Huoban\Models\HuobanItem;
use Huoban\Models\HuobanMembers;
use Huoban\Models\HuobanNotification;
use Huoban\Models\HuobanOrder;
use Huoban\Models\HuobanProcedure;
use Huoban\Models\HuobanShare;
use Huoban\Models\HuobanSpace;
use Huoban\Models\HuobanStorage;
use Huoban\Models\HuobanStream;
use Huoban\Models\HuobanTable;
use Huoban\Models\HuobanToken;
use Huoban\Models\HuobanUser;

class Huoban
{
    use \Huoban\StandardComponent\Config;
    use \Huoban\StandardComponent\HuobanStandardConfig;

    protected $request;
    public $models;
    public function __construct( $config = [] )
    {
        $this->config  = $config + $this->getStandardConfig();
        $this->request = new GuzzleRequest( $this->config );

        $this->initTicket();
    }

    public function initTicket()
    {
        $ticket = $this->getConfig( 'ticket' );
        if ( ! $ticket ) {
            $huoban_ticket = new HuobanTicket( $this->request );
            $ticket        = $huoban_ticket->getTicket();
        }

        $this->setConfig( 'ticket', $ticket );
        $this->request->setConfig( 'ticket', $ticket );
    }

    public function getHuobanBasic() : HuobanBasic
    {
        return new HuobanBasic( $this->request );
    }
    public function getHuobanBi() : HuobanBi
    {
        return new HuobanBi( $this->request );
    }
    public function getHuobanBiFile() : HuobanBiFile
    {
        return new HuobanBiFile( $this->request );
    }
    public function getHuobanBiItem() : HuobanBiItem
    {
        return new HuobanBiItem( $this->request );
    }
    public function getHuobanBiTable() : HuobanBiTable
    {
        return new HuobanBiTable( $this->request );
    }
    public function getHuobanComment() : HuobanComment
    {
        return new HuobanComment( $this->request );
    }
    public function getHuobanCompany() : HuobanCompany
    {
        return new HuobanCompany( $this->request );
    }
    public function getHuobanFile() : HuobanFile
    {
        return new HuobanFile( $this->request );
    }
    public function getHuobanFollow() : HuobanFollow
    {
        return new HuobanFollow( $this->request );
    }
    public function getHuobanItem() : HuobanItem
    {
        return new HuobanItem( $this->request );
    }
    public function getHuobanMembers() : HuobanMembers
    {
        return new HuobanMembers( $this->request );
    }
    public function getHuobanNotification() : HuobanNotification
    {
        return new HuobanNotification( $this->request );
    }
    public function getHuobanOrder() : HuobanOrder
    {
        return new HuobanOrder( $this->request );
    }
    public function getHuobanProcedure() : HuobanProcedure
    {
        return new HuobanProcedure( $this->request );
    }
    public function getHuobanShare() : HuobanShare
    {
        return new HuobanShare( $this->request );
    }
    public function getHuobanSpace() : HuobanSpace
    {
        return new HuobanSpace( $this->request );
    }
    public function getHuobanStorage() : HuobanStorage
    {
        return new HuobanStorage( $this->request );
    }
    public function getHuobanStream() : HuobanStream
    {
        return new HuobanStream( $this->request );
    }
    public function getHuobanTable() : HuobanTable
    {
        return new HuobanTable( $this->request );
    }
    public function getHuobanTicket() : HuobanTicket
    {
        return new HuobanTicket( $this->request );
    }
    public function getHuobanToken() : HuobanToken
    {
        return new HuobanToken( $this->request );
    }
    public function getHuobanUser() : HuobanUser
    {
        return new HuobanUser( $this->request );
    }
}