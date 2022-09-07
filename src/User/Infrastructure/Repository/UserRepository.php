<?php

namespace App\User\Infrastructure\Repository;

use App\User\Model\User;
use App\User\Model\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\Uuid;

class UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findByEmail(Email $email) : ?User
    {
        return $this->createQueryBuilder()
            ->andWhere('u.email.email = :email')
            ->setParameter('email', $email->address())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findById(Uuid $id): ?User
    {
        return $this->createQueryBuilder()
            ->andWhere('u.id.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }

    private function createQueryBuilder() : QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u');
    }
}
