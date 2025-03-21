<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use App\Application\Shared\Validator\AbstractRequestConstraint;

#[\Attribute]
class GetAccountRequestConstraint extends AbstractRequestConstraint
{
}