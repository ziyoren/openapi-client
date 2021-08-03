<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use ziyoren\OpenapiInterfaces\Request as ApiRequest;
use ziyoren\Openapi\Client;


/**
 * 一个测试接口
 */
class ApiTest extends ApiRequest
{
    protected $apiUrl = '/agbapi/index/me';

    protected $allowParams = [
        // 'fieldNmae' => ['0是否必填', '1类型', '2参数示例', '3默认址', '4参数描述', '5错误代码']
        'account'       => [true, 'String', '会员手机号', '无', '账号', 60650],
        'memberId'      => [true, 'String', '107084', '无', '会员号', 60651],
        'distrCode'     => [true, 'String', '', '无', '分销商编号', 60652],
    ];
}




$config = require_once __DIR__ . '/config.php';

$client = new Client($config);

$api = new ApiTest();
$data = [
    'account'   => '18988889999',
    'memberId'  => 123456,
    'distrCode' => 'ZIYO.REN',
];
$api->setParams($data);

$rst = $client->exectue($api);

echo json_encode($rst, 256|128|JSON_UNESCAPED_SLASHES);