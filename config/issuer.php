<?php

declare(strict_types=1);

return [

    'name' => env('ISSUER_NAME', 'Example Issuer'),

    'url' => env('ISSUER_URL', 'http://host.docker.internal:8497'),

    'did' => env('ISSUER_DID', 'did:example:123456789abcdefghi'),

    /**
     * The path to the private key used to sign the verifiable credentials.
     * This should be a PEM file containing the private key.
     */
    'private_key_path' => env('ISSUER_PRIVATE_KEY_PATH', ''),
];
