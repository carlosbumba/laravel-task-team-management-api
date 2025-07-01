<?php

namespace Auth\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;


#[Group('Autenticação', weight: 3)]
class LogoutController
{
    use ApiResponseHelpers;

    /**
     * Logout do usuário autenticado
     *
     * Revoga o token de autenticação atual do usuário, encerrando sua sessão.
     *
     * @response array{message: 'Logout realizado'}
     *
     * @authenticated
     *
     */

    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();
        $token?->delete();
        return $this->respondOk('Logout realizado');
    }
}
