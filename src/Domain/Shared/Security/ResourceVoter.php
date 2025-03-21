<?php

declare(strict_types=1);

namespace App\Domain\Shared\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Domain\Core\User\Entity\User;
use Ramsey\Uuid\Uuid;

class ResourceVoter extends Voter
{
    public const string UPDATE = 'update';
    public const string VIEW = 'view';
    public const string DELETE = 'delete';

    public function __construct(
        private readonly Security               $security,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return is_array($subject) && !empty($subject['repo']) && class_exists($subject['repo'])
            && !empty($subject['uuid'])
            && is_subclass_of($subject['repo'], OwnedResourceInterface::class)
            && in_array($attribute, [self::UPDATE, self::VIEW, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) return true;
        if (!Uuid::isValid($subject['uuid'])) return false;

        $repo = $this->em->getRepository($subject['repo']);
        $object = $repo->findOneBy(['uuid' => Uuid::fromString($subject['uuid'])]);

        return $object->getOwnerId() === $user->getId();
    }
} 