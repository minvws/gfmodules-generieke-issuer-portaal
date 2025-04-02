<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\ContextualAttribute;
use Jose\Component\KeyManagement\JWKFactory;

#[Attribute(Attribute::TARGET_PARAMETER)]
class JWK implements ContextualAttribute
{
    /**
     * Create a new attribute instance.
     */
    public function __construct(public string $key, public mixed $default = null)
    {
    }

    /**
     * Resolve the configuration value.
     *
     * @param self $attribute
     * @param Container $container
     * @return mixed
     * @throws BindingResolutionException
     */
    public static function resolve(self $attribute, Container $container): mixed
    {
        $privateKeyPath = Config::resolve(new Config($attribute->key, $attribute->default), $container);
        if (empty($privateKeyPath)) {
            throw new BindingResolutionException('Configuration value not found for key: ' . $attribute->key);
        }

        if (!file_exists($privateKeyPath)) {
            throw new BindingResolutionException('Private key file not found at path: ' . $privateKeyPath);
        }

        return JWKFactory::createFromKeyFile($privateKeyPath);
    }
}
