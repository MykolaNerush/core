<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Security\Voter;

use App\Domain\Core\Security\Enum\Permission;
use App\Domain\Core\User\Entity\User;
use App\Domain\Shared\Entity\OwnableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ResourceVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        // Перевіряємо чи атрибут є одним з наших Permission
        if (!in_array($attribute, array_column(Permission::cases(), 'value'), true)) {
            return false;
        }

        // Перевіряємо чи ресурс реалізує інтерфейс OwnableInterface
        if (!$subject instanceof OwnableInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var OwnableInterface $resource */
        $resource = $subject;

        // Отримуємо ролі користувача
        $userRoles = $user->getRoles();

        // Перевіряємо права на основі ролей
        return match ($attribute) {
            Permission::VIEW->value => $this->canView($userRoles, $resource, $user),
            Permission::EDIT->value => $this->canEdit($userRoles, $resource, $user),
            Permission::DELETE->value => $this->canDelete($userRoles, $resource, $user),
            Permission::CREATE->value => $this->canCreate($userRoles),
            Permission::MANAGE->value => $this->canManage($userRoles),
            Permission::MODERATE->value => $this->canModerate($userRoles),
            Permission::ADMIN->value => $this->canAdmin($userRoles),
            default => false,
        };
    }

    private function canView(array $roles, OwnableInterface $resource, User $user): bool
    {
        // Адміни та модератори можуть переглядати все
        if (in_array('ROLE_ADMIN', $roles, true) || in_array('ROLE_MODERATOR', $roles, true)) {
            return true;
        }

        // Власник може переглядати свої ресурси
        return $resource->getOwnerId()->equals($user->getUuid());
    }

    private function canEdit(array $roles, OwnableInterface $resource, User $user): bool
    {
        // Адміни можуть редагувати все
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return true;
        }

        // Модератори можуть редагувати деякі ресурси
        if (in_array('ROLE_MODERATOR', $roles, true)) {
            return $this->canModeratorEdit($resource);
        }

        // Власник може редагувати свої ресурси
        return $resource->getOwnerId()->equals($user->getUuid());
    }

    private function canDelete(array $roles, OwnableInterface $resource, User $user): bool
    {
        // Адміни можуть видаляти все
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return true;
        }

        // Власник може видаляти свої ресурси
        return $resource->getOwnerId()->equals($user->getUuid());
    }

    private function canCreate(array $roles): bool
    {
        return in_array('ROLE_ADMIN', $roles, true) ||
            in_array('ROLE_MODERATOR', $roles, true) ||
            in_array('ROLE_USER', $roles, true);
    }

    private function canManage(array $roles): bool
    {
        return in_array('ROLE_ADMIN', $roles, true) ||
            in_array('ROLE_MODERATOR', $roles, true);
    }

    private function canModerate(array $roles): bool
    {
        return in_array('ROLE_ADMIN', $roles, true) ||
            in_array('ROLE_MODERATOR', $roles, true);
    }

    private function canAdmin(array $roles): bool
    {
        return in_array('ROLE_ADMIN', $roles, true);
    }

    private function canModeratorEdit(OwnableInterface $resource): bool
    {
        // Тут можна додати специфічну логіку для модераторів
        // Наприклад, перевірка типу ресурсу або інші умови
        return true;
    }
}