<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\User;
use App\Entity\User\VO\Identity;
use App\Service\Clock;
use App\Tests\Service\StaticImmutableClock;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private ?User $sut;
    private ?Clock $clock;

    protected function setUp(): void
    {
        $this->clock = new StaticImmutableClock();

        $this->sut = new User(
            aUuid('e7600629-7b5a-4293-bb19-3e7ec9db21a4'),
            new Identity('Sami', 'Jnih'),
            $this->clock->now(),
            null
        );
    }

    public function testCreate(): void
    {
        $actual = User::create(
            aUuid('e7600629-7b5a-4293-bb19-3e7ec9db21a4'),
            new Identity('Sami', 'Jnih'),
            $this->clock
        );

        static::assertEquals($this->sut, $actual);
    }
}
