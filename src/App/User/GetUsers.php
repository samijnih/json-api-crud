<?php

declare(strict_types=1);

namespace App\User;

interface GetUsers
{
    /** @return User[] */
    public function getAll(int $limit = 15): array;
}
