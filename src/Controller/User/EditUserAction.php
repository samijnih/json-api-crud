<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\JsonApi\JsonApiValidator;
use App\Facade\UserFacade;
use App\User\EditUser;
use Laminas\Code\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EditUserAction
{
    private EditUser $editUser;
    private JsonApiValidator $validator;
    private string $schema = __DIR__.'/schema/edit_user_action.json';

    public function __construct(
        UserFacade $userFacade,
        JsonApiValidator $validator
    ) {
        $this->editUser = $userFacade;
        $this->validator = $validator;
    }

    /**
     * @Route("/users/{id}", methods={"PATCH"})
     */
    public function __invoke(string $id, Request $request): Response
    {
        try {
            $body = $this->validator->validateRequest($this->schema, $request);
        } catch (RuntimeException $e) {
            return new JsonResponse([$e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->editUser->edit(
            $id,
            $body->data->attributes->first_name,
            $body->data->attributes->last_name
        );

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
