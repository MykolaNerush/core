<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Validator\Constrains;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Domain\Core\Video\Validator\Constrains\DeleteVideoRequestConstraint;
use App\UI\Http\Rest\Internal\DTO\Auth\RevokeTokenRequest;
use App\UI\Http\Rest\Internal\DTO\Video\DeleteVideoRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ramsey\Uuid\Uuid;

class RevokeTokenRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof RevokeTokenRequest) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        $token = $request->headers->get('Authorization');
        if (!$token) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return;
        }

        $token = str_replace('Bearer ', '', $token);
        if (empty($token)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}