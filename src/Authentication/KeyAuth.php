<?php

namespace ziyoren\Openapi\Authentication;

use ziyoren\Openapi\Config\KeyAuthConfig;

class KeyAuth
{

    private $config = null;
    private $options;

    public function __construct(KeyAuthConfig $config, $options=[])
    {
        $this->config = $config;
        $this->options = $options;
    }

    public function getHeaders()
    {
        $headers = isset($this->options['headers']) ? $this->options['headers'] : [];
        $authHeader = [
            $this->config->getKeyName() => $this->config->getKey(),
        ];
        return array_merge($headers, $authHeader) ;
    }

}