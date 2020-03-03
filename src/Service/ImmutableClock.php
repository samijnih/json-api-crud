<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;

final class ImmutableClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
