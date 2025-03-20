<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use App\Domain\Core\User\Entity\User;
use App\UI\Http\Rest\Internal\DTO\Account\CreateAccountRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Bundle\SecurityBundle\Security;

class CreateAccountRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly Security $security,
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof CreateAccountRequest) {
            return;
        }
        /* @var User $user */
        $user = $this->security->getUser();
        
        /* @var CreateAccountRequestConstraint $constraint */
        if ($user->getAccount()) {
            $this->context->buildViolation($constraint->messageAccountExists)->addViolation();
        }
    }
}