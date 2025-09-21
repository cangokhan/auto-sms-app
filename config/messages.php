<?php

return [
    'webhook_url' => env('MESSAGE_WEBHOOK_URL'),
    'auth_key' => env('MESSAGE_AUTH_KEY'),
    'char_limit' => env('MESSAGE_CHAR_LIMIT'),
    'send_interval' => env('MESSAGE_SEND_INTERVAL'),
    'batch_size' => env('MESSAGE_BATCH_SIZE'),
];
