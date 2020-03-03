<?php

declare(strict_types=1);

namespace App\User;

interface RemoveUser
{
    public function remove(string $id): void;
}
