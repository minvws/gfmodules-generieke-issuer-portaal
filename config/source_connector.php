<?php

declare(strict_types=1);

return [
    'url' => env('SOURCE_CONNECTOR_URL', false),

    'mtls_cert' => env('SOURCE_CONNECTOR_CERT', false),
    'mtls_key' => env('SOURCE_CONNECTOR_KEY', false),
    'mtls_cacert' => env('SOURCE_CONNECTOR_CACERT', false),
    'mtls_verify_peer_cert' => env('SOURCE_CONNECTOR_VERIFY_PEER_CERT', true),
];
