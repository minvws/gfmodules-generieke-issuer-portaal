<?php

declare(strict_types=1);

return [

    'name' => env('ISSUER_NAME', 'Example Issuer'),

    'url' => env('ISSUER_URL', 'http://issuer-api:8497'),

    'custom_did' => env('ISSUER_CUSTOM_DID'),

    /**
     * The path to the private key used to sign the verifiable credentials.
     * This should be a PEM file containing the private key.
     */
    'private_key_path' => env('ISSUER_PRIVATE_KEY_PATH', ''),
];
