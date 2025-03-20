<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\UI\Http\Rest\Internal\DTO\Account\UpdateAccountRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ramsey\Uuid\Uuid;

class AccountUpdateRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof UpdateAccountRequest) {
            return;
        }
        $account = $this->accountRepository->findOneBy(['uuid' => Uuid::fromString($value->uuid)->getBytes()]);
        /* @var AccountUpdateRequestConstraint $constraint */
        if (!$account) {
            $this->context->buildViolation($constraint->messageAccountNotExists)->addViolation();
        }
    }
}