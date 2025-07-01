<?php

namespace Auth\Interface\Http\Controllers\API\V1;

use Auth\Application\DTOs\UserRegisterDTO;
use Auth\Application\Services\AccessTokenService;
use Auth\Application\UseCases\RegisterCase;
use Auth\Interface\Http\Requests\V1\RegisterRequest;
use User\Interface\Http\Resources\V1\UserResource;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Dedoc\Scramble\Attributes\Group;

#[Group('Autenticação', weight: 2)]
class RegisterController
{
    use ApiResponseHelpers;

    /**
     * Registrar novo usuário
     *
     * Cria uma nova conta de usuário e retorna o token de acesso para autenticação via Sanctum.
     *
     * @status 201
     * @response array{user: UserResource, token_type: 'Bearer' , access_token: string}
     * @unauthenticated
     *
     */
    public function __invoke(RegisterRequest $request, RegisterCase $userCase, AccessTokenService $service): JsonResponse
    {
        $user = $userCase->execute(UserRegisterDTO::fromRequest($request));

        $acess_token = $service->generateAccessToken($user->id);

        return $this->respondCreated([
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'access_token' => $acess_token,
        ]);
    }
}
