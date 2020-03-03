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

final class GetUserQueryTest extends WebTestCase
{
    use TestDatabaseHelper;

    protected function setUp()
    {
        $this->freshDatabase();
    }

    public function testICanModifyAnExistingUser(): void
    {
        $id = Uuid::uuid4();
        $firstName = 'Sami';
        $lastName = 'Jnih';
        $createdAt = (new ImmutableClock())->now();

        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $userRepository->create(
            new User(
                $id,
                new Identity($firstName, $lastName),
                $createdAt,
                null
            )
        );

        $client->request(
            'GET',
            "/users/$id",
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $expected = str_replace([
            '%id%',
            '%first_name%',
            '%last_name%',
            '%created_at%',
        ], [
            $id,
            $firstName,
            $lastName,
            $createdAt->format(DATE_ATOM)
        ], file_get_contents(__DIR__.'/responses/get_user.json'));
        $this->assertJsonStringEqualsJsonString($expected, $client->getResponse()->getContent());
    }
}
