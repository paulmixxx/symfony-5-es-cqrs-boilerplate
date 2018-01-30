<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Query\Mysql;

use App\Domain\User\Query\UserView;
use App\Domain\User\Query\Repository\UserReadModelRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class MysqlUserReadModelRepository extends MysqlRepository implements UserReadModelRepositoryInterface
{
    public function oneByUuid(UuidInterface $uuid): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    public function oneByEmail(Email $email): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $email->toString())
        ;

        return $this->oneOrException($qb);
    }

    public function add(UserView $userRead): void
    {
        $this->register($userRead);
    }

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);

        $this->setRepository(UserView::class);
    }
}
