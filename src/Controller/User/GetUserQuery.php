<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\User\GetUser;
use JsonApiPhp\JsonApi\Attribute;
use JsonApiPhp\JsonApi\DataDocument;
use JsonApiPhp\JsonApi\JsonApi;
use JsonApiPhp\JsonApi\Link\SelfLink;
use JsonApiPhp\JsonApi\ResourceObject;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetUserQuery
{
    private GetUser $getUser;

    public function __construct(GetUser $getUser)
    {
        $this->getUser = $getUser;
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        try {
            $user = $this->getUser->get($id);
        } catch (RuntimeException $e) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $data = new DataDocument(
            new ResourceObject(
                'users',
                $id,
                new Attribute('first_name', $user->getFirstName()),
                new Attribute('last_name', $user->getLastName()),
                new Attribute('created_at', $user->getCreatedAt()),
                new Attribute('updated_at', $user->getUpdatedAt()),
            ),
            new SelfLink("/users/$id"),
            new JsonApi()
        );

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
