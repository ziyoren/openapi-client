<?php

namespace ziyoren\Openapi\Config;

class KeyAuthConfig
{
    protected $key = '';
    protected $key_name = 'apikey';

    public function __construct($key, $key_name='')
    {
        $this->setKey($key);
        $this->setKeyName(empty($key_name) ? 'apikey' : $key_name);
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKeyName($key_name)
    {
        $this->key_name = $key_name;
    }

    public function getKeyName()
    {
        return $this->key_name;
    }

}
