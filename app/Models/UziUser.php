<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\UziNoUziNumberException;
use App\Services\Uzi\UziRoleService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;

class UziUser implements Authenticatable
{
    /**
     * @param string|null $initials
     * @param string|null $surname
     * @param string|null $surnamePrefix
     * @param string $uziId
     * @param string|null $loaAuthn
     * @param string $loaUzi
     * @param UziRelation[] $uras
     * @param string $email
     */
    public function __construct(
        public string|null $initials,
        public string|null $surname,
        public string|null $surnamePrefix,
        public string $uziId,
        public string|null $loaAuthn,
        public string $loaUzi,
        public array $uras,
        public string $email = ''
    ) {
        if (empty($email)) {
            $this->email = $uziId . '@uzi.pas';
        }
    }

    /**
     * @param object{
     *     relations: array<int, object{entity_name: string, ura: string, roles: string[]}>,
     *     initials: ?string,
     *     surname: ?string,
     *     surname_prefix: ?string,
     *     uzi_id: string,
     *     loa_uzi: string,
     *     loa_authn: string
     * } $oidcResponse
     * @throws UziNoUziNumberException
     */
    public static function deserializeFromObject(object $oidcResponse): ?UziUser
    {
        $requiredKeys = ["relations", "initials", "surname", "surname_prefix", "uzi_id", "loa_uzi"];
        $missingKeys = [];
        foreach ($requiredKeys as $key) {
            if (!property_exists($oidcResponse, $key)) {
                $missingKeys[] = $key;
            }
        }
        if (count($missingKeys) > 0) {
            Log::error("Uzi user missing required fields: " . implode(", ", $missingKeys));
            throw new UziNoUziNumberException();
        }


        $roleService = new UziRoleService();
        $uras = [];
        try {
            foreach ($oidcResponse->relations ?? [] as $ura) {
                if (
                    !property_exists($ura, "entity_name")
                    || !property_exists($ura, "ura")
                    || !property_exists($ura, "roles")
                ) {
                    Log::error("Uzi relation missing required fields: entity_name, ura or roles.", [$ura]);
                    continue;
                }

                $roles = $roleService->getRolesByCodes($ura->roles);
                $uras[] = new UziRelation($ura->entity_name, $ura->ura, $roles);
            }

            return new UziUser(
                $oidcResponse->initials,
                $oidcResponse->surname,
                $oidcResponse->surname_prefix,
                $oidcResponse->uzi_id,
                $oidcResponse->loa_authn ?? null,
                $oidcResponse->loa_uzi,
                $uras
            );
        } catch (Exception $e) {
            report($e);
            Log::error("Trying to build an uzi user from userinfo failed", [$e]);
            return null;
        }
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->initials . " " . $this->surnamePrefix . " " . $this->surname;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return $this->uziId;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifier(): string
    {
        return $this->uziId;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        throw new RuntimeException("Uzi uses can't have a password");
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

    public function hasUziId(): bool
    {
        return !empty($this->uziId);
    }

    public function getDisplayName(): string
    {
        if (empty($this->initials) || empty($this->surname)) {
            return $this->uziId;
        }

        return $this->initials
            . ($this->surnamePrefix ? " " . $this->surnamePrefix : "")
            . " " . $this->surname;
    }

    public function hasRelations(): bool
    {
        return count($this->uras) > 0;
    }
}
