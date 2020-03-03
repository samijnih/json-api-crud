<?php

declare(strict_types=1);

namespace App\Facade;

use App\Entity\User\Repository\UserRepository;
use App\Entity\User\User;
use App\Entity\User\VO\Identity;
use App\Service\Clock;
use App\User\EditUser;
use App\User\RegisterUser;
use Ramsey\Uuid\Uuid;

final class UserFacade implements RegisterUser, EditUser
{
    private UserRepository $entityRepository;
    private Clock $mutableClock;
    private Clock $immutableClock;

    public function __construct(
        UserRepository $entityRepository,
        Clock $mutableClock,
        Clock $immutableClock
    ) {
        $this->entityRepository = $entityRepository;
        $this->mutableClock = $mutableClock;
        $this->immutableClock = $immutableClock;
    }

    public function register(string $firstName, string $lastName): void
    {
        $user = User::create(
            $this->entityRepository->generateId(),
            new Identity($firstName, $lastName),
            $this->immutableClock
        );

        $this->entityRepository->create($user);
    }

    public function edit(string $id, string $firstName, string $lastName): void
    {
        $user = $this->entityRepository->findOne(Uuid::fromString($id));

        $user->changeIdentity(new Identity($firstName, $lastName), $this->mutableClock);

        $this->entityRepository->update($user);
    }
}
