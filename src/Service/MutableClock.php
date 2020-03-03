<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;

final class MutableClock implements Clock
{
    public function now(): DateTime
    {
        return new DateTime();
    }
}
