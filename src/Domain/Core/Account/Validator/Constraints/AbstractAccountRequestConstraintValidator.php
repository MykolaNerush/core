<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Ramsey\Uuid\Uuid;

abstract class AbstractAccountRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        protected readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    protected function validateExists(string $uuid, string $message): void
    {
        $account = $this->accountRepository->findOneBy(['uuid' => Uuid::fromString($uuid)->getBytes()]);
        if (!$account) {
            $this->context->buildViolation($message)->addViolation();
        }
    }
} 