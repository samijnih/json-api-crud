<?php

declare(strict_types=1);

namespace App\Entity\User\Repository;

use App\Entity\User\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function generateId(): UuidInterface;
    public function create(User $user): void;
}
