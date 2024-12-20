<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMINISTRATEUR = 'ADMINISTRATEUR';
    case ENQUETEUR = 'ENQUETEUR';
    case UTILISATEUR = 'UTILISATEUR';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}