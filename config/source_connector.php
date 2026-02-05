<?php

declare(strict_types=1);

return [
    'url' => env('SOURCE_CONNECTOR_URL', false),
    'endpoint' => env('SOURCE_CONNECTOR_ENDPOINT', '/enrich'),
    'mtls_cert' => env('SOURCE_CONNECTOR_CERT', false),
    'mtls_key' => env('SOURCE_CONNECTOR_KEY', false),
    'verify_ca' =>  str_starts_with(env('SOURCE_CONNECTOR_URL', ''), 'http')
    && empty(env('SOURCE_CONNECTOR_VERIFY_PEER_CERT')) ? false : env('SOURCE_CONNECTOR_VERIFY_PEER_CERT', true),
];
