<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\User\Repository;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Enum\Status;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Core\User\Transformer\FromEntity\UserTransformer;
use App\Infrastructure\Shared\Mailer\UserMailer;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends MysqlRepository<User>
 */
final class UserRepository extends MysqlRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserMailer                  $mailer,
        /** @var class-string<User> */
        protected string                             $class = User::class,
    )
    {
        parent::__construct($this->em);
    }

    public function page(
        callable       $routeGenerator,
        int            $page,
        int            $perPage,
        string         $order,
        string         $sort,
        ?UuidInterface $uuidSearch = null,
        string         $emailSearch = null
    ): PaginatedData
    {
        return $this->getFilteredPaginatedData(
            $this->getFilteredQueryBuilder($page, $perPage, $order, $sort, [
                ['uuid', $uuidSearch],
                ['email', ['LIKE', (null !== $emailSearch) ? '%' . $emailSearch . '%' : null], 'email'],
            ]),
            $routeGenerator,
            new UserTransformer(),
            'users'
        );
    }

    public function confirmEmail(string $email): void
    {
        /* @var User $user */
        $user = $this->em->getRepository($this->class)->findOneBy(['email' => urldecode($email)]);
        $user->setIsEmailConfirmed(true);
        $user->setConfirmationToken(null);
        $user->setStatus(Status::ACTIVE);
        $this->flush();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function createUser(string $name, string $email, string $password): User
    {
        $user = new User(
            $name,
            $email,
            $password,
            Status::NEW,
        );
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $this->create($user);
        $this->mailer->sendConfirmationEmail($user);
        return $user;
    }

}
