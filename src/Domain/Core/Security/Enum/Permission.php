<?php

declare(strict_types=1);

namespace App\Domain\Core\Security\Enum;

enum Permission: string
{
    case VIEW = 'view';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case CREATE = 'create';
    case MANAGE = 'manage';
    case MODERATE = 'moderate';
    case ADMIN = 'admin';
}