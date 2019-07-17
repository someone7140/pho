<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => [config('const_api.VIEW_DOMAIN')],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['Origin', 'Content-Type', 'Authorization'],
    'allowedMethods' => ['GET', 'POST', 'PUT', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];
