<?php

namespace ziyoren\Openapi\Config;

class HmacAuthConfig
{

    protected $username = '';
    protected $secret = '';
    protected $validate_request_body = false;
    protected $algorithms = 'sha256';

    public function __construct($username, $secret)
    {
        $this->setUsername($username);
        $this->setSecret($secret);
    }

    public function validate_request_body($tf)
    {
        $this->validate_request_body = $tf;
    }

    public function validate_body()
    {
        return $this->validate_request_body;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setAlgorithms($algorithms)
    {
        $this->algorithms = $algorithms;
    }

    public function getAlgorithms()
    {
        return $this->algorithms ;
    }

}