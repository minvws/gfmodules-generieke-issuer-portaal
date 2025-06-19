<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;
use JsonException;

class User implements Authenticatable
{
    /**
     * @param string $userinfo
     */
    public function __construct(
        public string $userinfo,
        public ?string $name = null,
        public ?string $organization_code = null,
    ) {
    }

    /**
     * @param object{
     *     userinfo: string
     * } $oidcResponse
     * @throws Exception
     */
    public static function deserializeFromObject(object $oidcResponse): ?User
    {
        $requiredKeys = ["userinfo"];
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
        $jsonDecoded = json_decode($oidcResponse->userinfo, true);

        return new User(
            $oidcResponse->userinfo,
            $jsonDecoded['name'] ?? null,
            $jsonDecoded['organization_code'] ?? null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getAsArray(): array
    {
        if ($this->userinfo === null) {
            return [];
        }

        try {
            return json_decode($this->userinfo, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
    }

    public function getUserInfo(): string
    {
        return $this->userinfo;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? $this->organization_code ?? 'Unknown User';
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return $this->userinfo;
    }


    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifier(): string
    {
        return $this->userinfo;
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
