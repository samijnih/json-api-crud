<?php

namespace App\Tests\Unit\Controller\User;

use App\Entity\User\Repository\UserRepository;
use App\Entity\User\User;
use App\Entity\User\VO\Identity;
use App\Service\ImmutableClock;
use App\Tests\Unit\TestDatabaseHelper;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class EditUserActionTest extends WebTestCase
{
    use TestDatabaseHelper;

    protected function setUp()
    {
        $this->freshDatabase();
    }

    public function testICanModifyAnExistingUser(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $userRepository->create(
            new User(
                $id = Uuid::uuid4(),
                new Identity('Sami', 'Jnih'),
                (new ImmutableClock())->now(),
                null
            )
        );

        $content = str_replace('%id%', $id, file_get_contents(__DIR__.'/requests/edit_user.json'));
        $client->request(
            'PATCH',
            "/users/$id",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            $content
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}
