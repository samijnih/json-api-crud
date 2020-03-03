<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\User\VO\Identity;
use App\Service\Clock;
use DateTime;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class User
{
    private string $id;
    private string $firstName;
    private string $lastName;
    private DateTimeImmutable $createdAt;
    private ?DateTime $updatedAt;

    public function __construct(
        UuidInterface $id,
        Identity $identity,
        DateTimeImmutable $createdAt,
        ?DateTime $updatedAt
    ) {
        $this->id = $id->toString();
        $this->firstName = $identity->firstName();
        $this->lastName = $identity->lastName();
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        UuidInterface $id,
        Identity $identity,
        Clock $clock
    ): self {
        return new self($id, $identity, $clock->now(), null);
    }

    public function changeIdentity(Identity $newIdentity, Clock $clock): void
    {
        $this->firstName = $newIdentity->firstName();
        $this->lastName = $newIdentity->lastName();
        $this->updatedAt = $clock->now();
    }
}
