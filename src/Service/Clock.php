<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeInterface;

interface Clock
{
    public function now(): DateTimeInterface;
}
