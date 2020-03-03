<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Clock;
use DateTimeImmutable;

final class StaticImmutableClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d', '2020-05-01');
    }
}
