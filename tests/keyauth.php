<?php
$path = dirname(__DIR__);
require_once $path . '/vendor/autoload.php';

use ziyoren\Openapi\Config\KeyAuthConfig;
use GuzzleHttp\Client;

$config = require_once __DIR__ . '/config.php';

$keyAuth = new KeyAuthConfig($config['key'], $config['key_name']);

echo $keyAuth->getKey(), PHP_EOL;


$client = new Client([
    'http_errors' => false,
    'base_uri' => $config['host'],
]);

$data = [
    'testhamc' => '123456',
];

$requestParams = [
    'headers' => [
        $keyAuth->getKeyName() => $keyAuth->getKey(),
        'content-type' => 'application/json',
    ],
    'json' => $data,
];

echo json_encode($requestParams, 256|JSON_UNESCAPED_SLASHES), PHP_EOL;

$res = $client->get('/agbapi/index/me', $requestParams);
echo $res->getStatusCode(), PHP_EOL;
echo $res->getBody(), PHP_EOL;

// var_dump($client);
