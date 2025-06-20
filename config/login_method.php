<?php

return [
    'enabled_methods' => explode(',', env('LOGIN_METHODS_ENABLED', 'openid4vp,mock,oidc')),
];
