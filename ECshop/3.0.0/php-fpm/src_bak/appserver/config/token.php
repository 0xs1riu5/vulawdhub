<?php
//
return [
    'secret' => env('TOKEN_SECRET', 'ecmobile'),
    'alg'    => env('TOKEN_ALG', 'HS256'),
    'ttl'    => env('TOKEN_TTL', 43200), // minutes
    'refresh'=> env('TOKEN_REFRESH', false), // minutes
    'refresh_ttl'=> env('TOKEN_REFRESH_TTL', 1440), // minutes
    'ver'    => env('TOKEN_VER', '1.0.0')
];