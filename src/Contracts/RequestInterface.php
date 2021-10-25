<?php

namespace Huoban\Contracts;

interface RequestInterface
{

    public function execute($method, $uri, $body = [], $options = []);

    public function getHttpClient($interface_type);

    public function getRequest($method, $url, $body = [], $options = []);

    public function requestJsonPool($requests, $interface_type = 'api', $concurrency = 20);

    public function requestJsonSync($request, $interface_type = '');

}
