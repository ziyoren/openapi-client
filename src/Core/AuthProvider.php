<?php

namespace ziyoren\Openapi\Core;

use ziyoren\Openapi\Config\KeyAuthConfig;
use ziyoren\Openapi\Config\HmacAuthConfig;

class AuthProvider
{
    protected $options;

    protected $config;

    protected $auth;

    public function __construct($options)
    {
        $this->options = $options;
        $this->checkConfig();
        if ( $this->isKeyAuth() ) {
            $this->config = new KeyAuthConfig($options['key'], $options['key_name']);
            $this->auth = \ziyoren\Openapi\Authentication\KeyAuth::class;
        }
        if ( $this->isHmacAuth() ) {
            $this->config = new HmacAuthConfig($options['username'], $options['secret']);
            $vbody = isset($options['validate_body']) ? $options['validate_body'] : false;
            $this->config->validate_request_body($vbody);
            $this->auth = \ziyoren\Openapi\Authentication\HmacAuth::class;
        }
    }

    public static function Instance($options)
    {
        return new static($options);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getHost()
    {
        return $this->options['host'];
    }

    public function authHandler()
    {
        return $this->auth;
    }

    public function getOptions()
    {
        return $this->options;
    }

    protected function checkConfig()
    {
        //todo check optionss
    }

    public function getAuthType()
    {
        return $this->options['auth_type'];
    }

    public function isKeyAuth()
    {
        return 'key-auth' == $this->getAuthType();
    }

    public function isHmacAuth()
    {
        return 'hmac-auth' == $this->getAuthType();
    }

    public function getHeaders($options = [])
    {
        if ($this->isKeyAuth()){
            $auth = new $this->auth($this->config);
            return $auth->getHeaders();
        }
        if ($this->isHmacAuth()){
            $auth = new $this->auth($this->config, $options);
            return $auth->getHeaders();
        }
        return isset($options['headers']) ? $options['headers'] : [];
    }

}