<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Facade\UserFacade;
use App\User\RemoveUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RemoveUserAction
{
    private RemoveUser $removeUser;

    public function __construct(UserFacade $userFacade)
    {
        $this->removeUser = $userFacade;
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function __invoke(string $id): Response
    {
        $this->removeUser->remove($id);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
