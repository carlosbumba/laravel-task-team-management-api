<?php

namespace User\Interface\Http\Controllers\API\V1;

use Auth\Infrastructure\Persistence\Model\User;
use User\Interface\Http\Resources\V1\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use User\Application\Services\UserService;
use User\Interface\Http\Requests\V1\UpdateUserRequest;

use Dedoc\Scramble\Attributes\Group;

#[Group('Usuários')]
class UserController
{
    use AuthorizesRequests;

    /**
     * Dados do usuário autenticado
     *
     * Retorna as informações do usuário atualmente autenticado.
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Listar usuários
     *
     * Retorna uma lista paginada de outros usuários no sistema. Acesso restrito por papel.
     */
    public function index(Request $request, UserService $service)
    {
        $this->authorize('viewAny', $request->user());

        return UserResource::collection($service->getAllOtherUsers($request->user()->id));
    }

    /**
     * Visualizar usuário
     *
     * Retorna os dados de um usuário específico, desde que autorizado.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Visualizar usuário
     *
     * Retorna os dados de um usuário específico, desde que autorizado.
     */
    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        $this->authorize('update', $user);

        return new UserResource($service->update($user, $request->only(['name', 'email'])));
    }
}
