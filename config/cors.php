<?php

return [

    'paths' => ['api/*', 'cashfree/*', 'updateEmi', 'payment/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ✅ Remove the trailing slash (this breaks matching)
    // ✅ And don't mix '*' with specific domains — use '*' only
    // Laravel doesn't handle both together properly
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // ✅ For API calls from Flutter Web, this must be false
    'supports_credentials' => false,
];
