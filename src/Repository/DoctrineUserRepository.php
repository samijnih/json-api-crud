<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\Repository\UserRepository;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository
{
    private UuidFactoryInterface $uuidFactory;

    public function __construct(ManagerRegistry $registry, UuidFactoryInterface $uuidFactory)
    {
        $this->uuidFactory = $uuidFactory;

        parent::__construct($registry, User::class);
    }

    public function generateId(): UuidInterface
    {
        return $this->uuidFactory->uuid4();
    }

    public function create(User $user): void
    {
        $this->getEntityManager()->transactional(fn (EntityManagerInterface $em) => $em->persist($user));
    }

    public function findOne(UuidInterface $id): User
    {
        $user = $this->findOneBy(['id' => $id->toString()]);

        if (null === $user) {
            throw new RuntimeException("User $id not found.");
        }

        return $user;
    }

    public function update(User $user): void
    {
        $this->getEntityManager()->flush();
    }
}
