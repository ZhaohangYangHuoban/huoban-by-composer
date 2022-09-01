<?php

namespace Huoban\StandardComponent;

trait Config
{
    protected $config;

    public function setConfig($key, $val)
    {
        $this->config[$key] = $val;
    }

    public function getConfig($key, $val = '')
    {
        return $this->config[$key] ?? $val;
    }
}
