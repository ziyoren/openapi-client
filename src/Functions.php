<?php

if (!function_exists('ziyo_user_agent')){
    function ziyo_user_agent()
    {
        static $defaultAgent = '';

        if (!$defaultAgent) {
            $defaultAgent = 'ZiyoApiHttp/' . \ziyoren\Openapi\Client::VERSION;
            if (extension_loaded('curl') && function_exists('curl_version')) {
                $defaultAgent .= ' curl/' . \curl_version()['version'];
            }
            $defaultAgent .= ' PHP/' . PHP_VERSION;
        }

        return $defaultAgent;
    }
}