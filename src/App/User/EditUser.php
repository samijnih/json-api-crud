<?php

declare(strict_types=1);

namespace App\User;

interface EditUser
{
    public function edit(string $id, string $firstName, string $lastName): void;
}
