<?php

return [
    'base_url' => env('GOODLUCK_API_URL', 'https://localhost'),
    'provider_code' => env('GOODLUCK_PROVIDER_CODE', 'goodluck'),
    'connect_timeout_seconds' => (int) env('GOODLUCK_CONNECT_TIMEOUT_SECONDS', 10),
    'timeout_seconds' => (int) env('GOODLUCK_TIMEOUT_SECONDS', 60),
];
