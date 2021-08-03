<?php

return [
    'host' => 'http://localhost:8000',

    // 'auth_type' => 'key-auth',  
    'key' => 'apikeytest', 
    'key_name' => 'appkey',

    'auth_type' => 'hmac-auth', 
    'username' => 'dev',
    'secret' => 'devtest',
    'validate_body' => true,
    'algorithms' => 'sha256',
];