<?php

declare(strict_types=1);

return [
    'enabled' => env('REVOCATION_SERVICE_ENABLED', false),
    'url' => env('REVOCATION_SERVICE_URL', ''),
];
