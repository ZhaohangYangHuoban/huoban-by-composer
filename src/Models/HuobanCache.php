<?php

namespace Huoban\Models;

use Closure;
use function GuzzleHttp\json_decode;

class HuobanCache
{
    public $_huoban;
    public $path;
    public function __construct($huoban)
    {
        $this->_huoban = $huoban;
        $this->setPath();
    }
    public function setPath()
    {
        $this->path = $this->_huoban->config['cache_path'] ?? '/tmp/huoban/';
        !is_dir($this->path) && mkdir($this->path, 0777, true);
    }

    public function set($name, $value, $expired = 0)
    {
        $file_name = $this->path . $name;
        $file_data = ['name' => $name, 'value' => $value, 'expired' => $expired, 'create_at' => time()];

        return file_put_contents($file_name, json_encode($file_data));
    }

    public function get($name, $default_value = null)
    {
        $file_name = $this->path . $name;
        if (!is_file($file_name)) {
            return $default_value;
        }
        $file_data = json_decode(file_get_contents($file_name), true);

        return (time() - $file_data['create_at']) <= $file_data['expired'] ? $file_data['value'] : null;
    }

    public function remember($name, $expired, $concrete)
    {

        $value = $this->get($name);

        if (!$value) {
            $value = $concrete instanceof Closure ? $concrete() : $concrete;
            $this->set($name, $value, $expired);
        }
        return $value;
    }
}
