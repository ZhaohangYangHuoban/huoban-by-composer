<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanMembers
{
    public $_huoban;

    public function __construct(Huoban $huoban)
    {
        $this->_huoban = $huoban;
    }

    /**
     * 获取工作区成员信息
     *
     * @param [type] $space_id
     * @param array $body
     * @return void
     */
    public function getMembersRequest($space_id, $body = [])
    {
        return $this->_huoban->getRequest('GET', "/space/{$space_id}/members", $body);
    }
    public function getMembers($space_id, $body = [])
    {
        return $this->_huoban->execute('GET', "/space/{$space_id}/members", $body);
    }

    public function getCompanyMembersRequest($company_id, $body = [])
    {
        return $this->_huoban->getRequest('GET', "/company_members/company/{$company_id}", $body);
    }
    public function getCompanyMembers($company_id, $body = [])
    {
        return $this->_huoban->execute('GET', "/company_members/company/{$company_id}", $body);
    }

    public function getMembersGroupsRequest($space_id, $body = [])
    {
        return $this->_huoban->getRequest('GET', "/members_and_groups/space/{$space_id}", $body);
    }
    public function getMembersGroups($space_id, $body = [])
    {
        return $this->_huoban->execute('GET', "/members_and_groups/space/{$space_id}", $body);
    }
}
