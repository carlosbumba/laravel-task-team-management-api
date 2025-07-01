<?php

namespace Auth\Interface\Http\Controllers\API\V1;

use Auth\Application\DTOs\UserLoginDTO;
use Auth\Application\UseCases\loginCase;
use Auth\Exceptions\InvalidPasswordException;
use Auth\Interface\Http\Requests\V1\LoginRequest;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Dedoc\Scramble\Attributes\Group;


#[Group('Autenticação', weight: 1)]
class LoginController
{
    use ApiResponseHelpers;

    /**
     * Autenticar usuário
     *
     * Realiza o login de um usuário e retorna o token de acesso para autenticação via Sanctum.
     *
     * @status 201
     * @response array{token_type: 'Bearer' , access_token: string}
     * @unauthenticated
     *
     */

    public function __invoke(LoginRequest $request, loginCase $userCase): JsonResponse
    {
        try {
            $access_token = $userCase->execute(UserLoginDTO::fromRequest($request));

            return $this->respondWithSuccess([
                'token_type' => 'Bearer',
                'access_token' => $access_token,
            ]);
        } catch (InvalidPasswordException $e) {
            return $this->respondFailedValidation($e->getMessage(), 'error');
        }
    }
}
