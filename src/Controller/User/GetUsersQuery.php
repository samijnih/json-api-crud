<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\User\GetUsers;
use App\User\User;
use JsonApiPhp\JsonApi\Attribute;
use JsonApiPhp\JsonApi\DataDocument;
use JsonApiPhp\JsonApi\JsonApi;
use JsonApiPhp\JsonApi\Link\SelfLink;
use JsonApiPhp\JsonApi\ResourceCollection;
use JsonApiPhp\JsonApi\ResourceObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetUsersQuery
{
    private GetUsers $getUsers;

    public function __construct(GetUsers $getUsers)
    {
        $this->getUsers = $getUsers;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function __invoke(): Response
    {
        $users = array_map(
            fn (User $user): ResourceObject => new ResourceObject(
                'users',
                $user->getId(),
                new Attribute('first_name', $user->getFirstName()),
                new Attribute('last_name', $user->getLastName()),
                new Attribute('created_at', $user->getCreatedAt()),
                new Attribute('updated_at', $user->getUpdatedAt()),
            ),
            $this->getUsers->getAll()
        );

        $data = new DataDocument(
            new ResourceCollection(...$users),
            new SelfLink('/users'),
            new JsonApi()
        );

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
