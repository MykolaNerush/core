<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Token;

enum TokenType: string
{
    case ACCESS = 'access';
    case REFRESH = 'refresh';
} 