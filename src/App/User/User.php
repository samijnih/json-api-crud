<?php

declare(strict_types=1);

namespace App\User;

use DateTimeImmutable;

final class User
{
    private string $id;
    private string $firstName;
    private string $lastName;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format(DATE_ATOM);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt ? $this->updatedAt->format(DATE_ATOM) : null;
    }
}
