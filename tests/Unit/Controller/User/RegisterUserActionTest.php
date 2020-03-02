<?php

namespace App\Tests\Unit\Controller\User;

use App\Tests\Unit\TestDatabaseHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RegisterUserActionTest extends WebTestCase
{
    use TestDatabaseHelper;

    protected function setUp()
    {
        $this->freshDatabase();
    }

    public function testICanRegisterANewUser(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/users',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            file_get_contents(__DIR__.'/requests/register_user.json')
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
