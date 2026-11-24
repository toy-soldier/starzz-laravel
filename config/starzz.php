<?php

return [
    'password_reset_sentinel' => env('STARZZ_PASSWORD_RESET_SENTINEL'),
    'jwt_secret' => env('STARZZ_JWT_SECRET'),
    'jwt_ttl' => 86400,

    'admins' => ['admin1', 'admin2', 'admin3'],
    'int_min' => -2147483648,
    'int_max' => 2147483647,
];