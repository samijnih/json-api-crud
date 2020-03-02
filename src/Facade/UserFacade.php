<?php

declare(strict_types=1);

namespace App\Facade;

use App\Entity\User\Repository\UserRepository;
use App\Entity\User\User;
use App\Entity\User\VO\Identity;
use App\Service\Clock;
use App\User\RegisterUser;

final class UserFacade implements RegisterUser
{
    private UserRepository $entityRepository;
    private Clock $clock;

    public function __construct(
        UserRepository $entityRepository,
        Clock $clock
    ) {
        $this->entityRepository = $entityRepository;
        $this->clock = $clock;
    }

    public function register(string $firstName, string $lastName): void
    {
        $user = User::create(
            $this->entityRepository->generateId(),
            new Identity($firstName, $lastName),
            $this->clock
        );

        $this->entityRepository->create($user);
    }
}
