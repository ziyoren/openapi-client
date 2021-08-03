<?php

$path = dirname(__DIR__);
require_once $path . '/vendor/autoload.php';

use ziyoren\Openapi\Config\HmacAuthConfig;
use ziyoren\Openapi\Authentication\HmacAuth;
use GuzzleHttp\Client;

$config = require_once __DIR__ . '/config.php';

$hamcConfig = new HmacAuthConfig($config['username'], $config['secret']);
$hamcConfig->validate_request_body($config['validate_body']);
$hamcConfig->setAlgorithms($config['algorithms']);

$data = 'A small body';
$options = [
    'headers' => [
        'testhamc' => '123456',
    ],
    'body' => $data,
];


$hamc = new HmacAuth($hamcConfig, $options);
$headers = $hamc->getHeaders();
print_r($headers);

$client = new Client([
    'http_errors' => false,
    'base_uri' => $config['host'],
]);

$requestParams = [
    'headers' => $headers,
    'body' => $data,
];

echo json_encode($requestParams, 256|JSON_UNESCAPED_SLASHES), PHP_EOL;

$res = $client->get('/agbapi/index/me', $requestParams);
echo $res->getStatusCode(), PHP_EOL;
echo $res->getBody(), PHP_EOL;