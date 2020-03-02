<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\JsonApi\JsonApiValidator;
use App\Facade\UserFacade;
use Laminas\Code\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterUserAction
{
    private UserFacade $userFacade;
    private JsonApiValidator $validator;
    private string $schema = __DIR__.'/schema/register_user_action.json';

    public function __construct(
        UserFacade $userFacade,
        JsonApiValidator $validator
    ) {
        $this->userFacade = $userFacade;
        $this->validator = $validator;
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        try {
            $body = $this->validator->validateRequest($this->schema, $request);
        } catch (RuntimeException $e) {
            return new JsonResponse([$e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->userFacade->register(
            $body->data->attributes->first_name,
            $body->data->attributes->last_name
        );

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
