<?php

declare(strict_types=1);

namespace App\Application\Shared\Validator;

use App\Infrastructure\Core\User\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @param mixed $value
     * @param UniqueEmail $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($this->userRepository->findOneBy(['email' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', is_string($value) ? $value : '')
                ->addViolation();
        }
    }
}
