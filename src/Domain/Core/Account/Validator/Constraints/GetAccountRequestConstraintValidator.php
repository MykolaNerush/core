<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use App\UI\Http\Rest\Internal\DTO\Account\UpdateAccountRequest;
use Symfony\Component\Validator\Constraint;

class GetAccountRequestConstraintValidator extends AbstractAccountRequestConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof UpdateAccountRequest) {
            return;
        }
        $this->validateExists($value->uuid, $constraint->getMessage());
    }
}