<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Validator\Constraints;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Shared\Services\User\UserConfirmationService;
use App\UI\Http\Rest\Internal\DTO\User\ConfirmEmailRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConfirmEmailRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly string                  $secretKey,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserConfirmationService $userConfirmationService
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof ConfirmEmailRequest) {
            return;
        }

        $email = urldecode($value->email);
        /* @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);
        /* @var ConfirmEmailRequestConstraint $constraint */
        if (!$user || !$this->userConfirmationService->verifyToken($user, $value->token)) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return;
        }
        $expectedSignature = hash_hmac('sha256', $email, $this->secretKey);
        if (!hash_equals($expectedSignature, $value->signature)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}