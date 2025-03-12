<?php

declare(strict_types=1);

namespace App\Models;

class UziRelation
{
    /**
     * @param string $entityName
     * @param string $ura
     * @param UziRelationRole[] $roles
     */
    public function __construct(
        public string $entityName,
        public string $ura,
        public array $roles
    ) {
    }

    public function getVisibleRoleNames(): string
    {
        return implode(', ', array_map(fn($role) => $role->name, $this->roles));
    }

    /**
     * @return string[]
     */
    public function getRoleCodes(): array
    {
        return array_map(fn($role) => $role->code, $this->roles);
    }
}
