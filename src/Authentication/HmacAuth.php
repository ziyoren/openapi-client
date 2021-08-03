<?php

namespace ziyoren\Openapi\Authentication;

use ziyoren\Openapi\Config\HmacAuthConfig;

class HmacAuth
{

    private $requestBody = '';
    private $config = null;
    private $signHeaderKeys = ['date'];
    private $options = [];

    public function __construct(HmacAuthConfig $config, $options=[])
    {
        $this->config = $config;
        $this->setOptions($options);
    }

    public function setOptions($options)
    {
        // echo 'in options: ', json_encode($options, 256), PHP_EOL;
        $this->options = is_array($options) ? $options : [];
        $headers = isset($this->options['headers']) ? $this->options['headers'] : [];
        $customHeaders = array_keys($headers);
        $this->options['headers']['Date'] = $this->getDate();
        array_push($customHeaders, 'Date');
        $this->requestBody = isset($options['body']) ? $options['body'] : '';
        // echo 'body1: ', $this->requestBody, PHP_EOL;
        if ($this->config->validate_body()){
            array_push($customHeaders, 'Digest');
            $this->options['headers']['Digest'] = $this->getDigest();
        }
        $this->setSignatureHeaders($customHeaders);
        // echo 'setOptions: ', json_encode($this->options, 256), PHP_EOL;
    }

    public function setSignatureHeaders($keys)
    {
        $this->signHeaderKeys = $keys;
    }

    protected function getSignatureTxt()
    {
        $headers = isset($this->options['headers']) ? 
                    $this->options['headers'] : [];
        foreach ($this->signHeaderKeys as $key ){
            $tmp[] = strtolower($key) . ': ' . trim($headers[$key]);
        }
        return implode("\n", $tmp);
    }

    public function getHeaders()
    {
        $headers = $this->options['headers'];
        $d = [   
            'Authorization' => $this->getAuthorization(),
        ];
        return array_merge($headers, $d);
    }

    protected function getDate()
    {
        $dz = date_default_timezone_get();
        date_default_timezone_set('GMT');
        $rst = date('D, d M Y H:i:s T');
        date_default_timezone_set($dz);
        return $rst;
    }

    protected function getAuthorization()
    {
        $username = $this->config->getUsername();
        $algo = $this->getAlgorithmsName();
        $signature = $this->getSignature();
        $headers = $this->getHeadersKeyString();
        return 'hmac username="'. $username .
                '", algorithm="hmac-'. $algo .
                '", headers="'. $headers .
                '", signature="'. $signature .'"';
    }

    public function getSignature()
    {
        $txt = $this->getSignatureTxt();
        return $this->hmac($txt);
    }

    public function getDigest()
    {
        $ms = [
            'sha256' => 'SHA-256',
        ];
        $algo = 'sha256'; 
        $mn = $ms[ $algo ];
        $dt = base64_encode(hash($algo, $this->requestBody, true));
        return $mn . '='. $dt;
    }

    public function hmac($txt)
    {
        $algo   = $this->getAlgorithmsName();
        $secret = $this->config->getSecret();
        return base64_encode( hash_hmac($algo, $txt, $secret, true) );
    }

    protected function getAlgorithmsName()
    {
        return $this->config->getAlgorithms();
    }

    protected function getHeadersKeyString()
    {
        return strtolower(implode(' ', $this->signHeaderKeys));
    }

}