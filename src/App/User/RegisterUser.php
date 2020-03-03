<?php

declare(strict_types=1);

namespace App\User;

interface RegisterUser
{
    public function register(string $firstName, string $lastName): void;
}
