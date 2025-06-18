<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;

class User implements Authenticatable
{
    /**
     * @param string $id
     * @param string $organizationCode
     */
    public function __construct(
        public string $id,
        public string $organizationCode,
    ) {
    }

    /**
     * @param object{
     *     id: string,
     *     organization_code: string
     * } $oidcResponse
     * @throws Exception
     */
    public static function deserializeFromObject(object $oidcResponse): ?User
    {
        $requiredKeys = ["id", "organization_code"];
        $missingKeys = [];
        foreach ($requiredKeys as $key) {
            if (!property_exists($oidcResponse, $key)) {
                $missingKeys[] = $key;
            }
        }
        if (count($missingKeys) > 0) {
            Log::error("User missing required fields: " . implode(", ", $missingKeys));
            throw new Exception("Missing required fields: " . implode(", ", $missingKeys));
        }

        return new User(
            $oidcResponse->id,
            $oidcResponse->organization_code
        );
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->organizationCode;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return $this->organizationCode;
    }


    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifier(): string
    {
        return $this->organizationCode;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        throw new RuntimeException("Users can't have a password");
    }

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName(): string
    {
        throw new RuntimeException("No password for users");
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        throw new RuntimeException("Do not remember cookie's");
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value): void
    {
        throw new RuntimeException("Do not remember cookie's");
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        throw new RuntimeException("Do not remember cookie's");
    }
}
