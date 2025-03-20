<?php

declare(strict_types=1);

namespace App\Domain\Shared\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Domain\Core\User\Entity\User;

class ResourceVoter extends Voter
{
    public const string UPDATE = 'update';
    public const string VIEW = 'view';
    public const string DELETE = 'delete';

    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof OwnedResourceInterface
            && in_array($attribute, [self::UPDATE, self::VIEW, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $subject->getOwnerId() === $user->getId();
    }
} 