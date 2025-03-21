<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Validator\Constraints;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\UI\Http\Rest\Internal\DTO\User\DeleteUserRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ramsey\Uuid\Uuid;

class UpdateUserRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof DeleteUserRequest) {
            return;
        }
        /* @var ?User $constraint */
        $account = $this->userRepository->findOneBy(['uuid' => Uuid::fromString($value->uuid)->getBytes()]);
        /* @var DeleteUserRequestConstraint $constraint */
        if (!$account) {
            $this->context->buildViolation($constraint->messageAccountNotExists)->addViolation();
        }
    }
}