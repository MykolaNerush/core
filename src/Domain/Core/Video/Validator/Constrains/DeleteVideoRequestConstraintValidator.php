<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Validator\Constrains;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\UI\Http\Rest\Internal\DTO\Video\DeleteVideoRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ramsey\Uuid\Uuid;

class DeleteVideoRequestConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly VideoRepositoryInterface $videoRepository,
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof DeleteVideoRequest) {
            return;
        }
        /* @var ?Video $constraint */
        $video = $this->videoRepository->findOneBy(['uuid' => Uuid::fromString($value->uuid)->getBytes()]);
        /* @var DeleteVideoRequestConstraint $constraint */
        if (!$video) {
            $this->context->buildViolation($constraint->messageVideoNotExists)->addViolation();
        }
    }
}