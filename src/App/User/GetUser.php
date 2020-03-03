<?php

declare(strict_types=1);

namespace App\User;

interface GetUser
{
    public function get(string $id): User;
}
