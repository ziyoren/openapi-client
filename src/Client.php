<?php

namespace ziyoren\Openapi;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Utils;
use ziyoren\Openapi\Core\AuthProvider;
use ziyoren\OpenapiInterfaces\Request as ApiRequest;


class Client
{
    const VERSION = '0.0.1';

    protected $authProvider;

    public function __construct($config)
    {
        $this->authProvider = AuthProvider::Instance($config);
    }

    
    public function exectue(ApiRequest $apiRequest)
    {
        $auth   = $this->authProvider;
        $client = new HttpClient([
            'http_errors' => false,
            'base_uri' => $auth->getHost(),
        ]);

        $params      = $apiRequest->getParams();
        $options     = [
            'headers' => $apiRequest->getHeaders(), 
            'body'    => Utils::jsonEncode($params),
        ];
        $headers     = $auth->getHeaders($options); 
        $http_method = $apiRequest->getMethod();
        $uri         = $apiRequest->getApiUrl();

        $requestParams = [
            'json' => $params,
        ];
        $headers['User-Agent'] = ziyo_user_agent();
        $requestParams['headers'] = $headers;

        $res = $client->request($http_method, $uri, $requestParams);
        $rst = Utils::jsonDecode($res->getBody(), true);
        $statusCode = $res->getStatusCode();
        
        if (is_null($rst)){
            $rst = [
                'code' => $statusCode, 
                'rawContent' => $res->getBody(), 
            ];
        }else{
            if (!isset($rst['code'])){
                $rst['code'] = $statusCode;
            }
        }
        return $rst;
    }



}