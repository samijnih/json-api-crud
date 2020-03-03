<?php

declare(strict_types=1);

namespace App\Facade;

use App\Entity\User\Repository\UserRepository;
use App\Entity\User\User;
use App\Entity\User\VO\Identity;
use App\Service\Clock;
use App\User\EditUser;
use App\User\RegisterUser;
use App\User\RemoveUser;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final class UserFacade implements RegisterUser, EditUser, RemoveUser
{
    private UserRepository $entityRepository;
    private Clock $mutableClock;
    private Clock $immutableClock;
    private LoggerInterface $domainLogger;

    public function __construct(
        UserRepository $entityRepository,
        Clock $mutableClock,
        Clock $immutableClock,
        LoggerInterface $domainLogger
    ) {
        $this->entityRepository = $entityRepository;
        $this->mutableClock = $mutableClock;
        $this->immutableClock = $immutableClock;
        $this->domainLogger = $domainLogger;
    }

    public function register(string $firstName, string $lastName): void
    {
        $user = User::create(
            $id = $this->entityRepository->generateId(),
            new Identity($firstName, $lastName),
            $this->immutableClock
        );

        $this->entityRepository->create($user);

        $this->domainLogger->info("User $id registered.");
    }

    public function edit(string $id, string $firstName, string $lastName): void
    {
        $user = $this->entityRepository->findOne(Uuid::fromString($id));

        $user->changeIdentity(new Identity($firstName, $lastName), $this->mutableClock);

        $this->entityRepository->update($user);

        $this->domainLogger->info("User $id edited.");
    }

    public function remove(string $id): void
    {
        $this->entityRepository->delete(Uuid::fromString($id));

        $this->domainLogger->info("User $id removed.");
    }
}
