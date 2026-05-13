<?php

namespace App\Enums;

enum RolesEnum: string
{
    case SUPERADMIN = 'super_admin';
    case AGENT = 'agent';
    case ENDUSER = 'end_user';

    public function getLabel(): string
    {
        return match ($this) {
            static::SUPERADMIN => 'Super Admin',
            static::AGENT => 'Agent',
            static::ENDUSER => 'End User',
        };
    }
}
