<?php

namespace Team\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Team\Application\DTOs\TeamMemberDTO;
use Team\Application\UseCases\AddTeamMemberUseCase;
use Team\Application\UseCases\GetTeamMembersUseCase;
use Team\Application\UseCases\GetTeamsForUserUseCase;
use Team\Application\UseCases\RemoveTeamMemberUseCase;
use Team\Exceptions\DuplicateTeamMemberException;
use Team\Exceptions\InvalidTeamMemberException;
use Team\Interface\Http\Requests\V1\TeamMemberCreateRequest;
use Team\Interface\Http\Resources\V1\TeamMemberResource;
use Team\Interface\Http\Resources\V1\TeamResource;
use Dedoc\Scramble\Attributes\Group;

#[Group('Membros de Equipe')]
class TeamMembersController
{
    use ApiResponseHelpers;

    /**
     * Listar membros de uma equipe
     *
     * @response array{data: TeamMemberResource[]}
     *
     * Retorna todos os usuários vinculados a uma equipe específica.
     */
    public function show(string $teamId, GetTeamMembersUseCase $useCase)
    {
        return TeamMemberResource::collection($useCase->execute($teamId));
    }

    /**
     * Adicionar membro à equipe
     *
     * @response array{data: TeamMemberResource}
     * @status 201
     *
     * Adiciona um novo usuário à equipe, desde que ele ainda não esteja vinculado.
     */
    public function add(TeamMemberCreateRequest $request, AddTeamMemberUseCase $useCase)
    {
        try {
            $dto = TeamMemberDTO::fromRequest($request, $request->route('id'));
            return new TeamMemberResource($useCase->execute($dto));
        } catch (DuplicateTeamMemberException $e) {
            return $this->respondFailedValidation($e->getMessage(), 'error');
        }
    }

    /**
     * Listar equipes do usuário autenticado
     *
     * Retorna todas as equipes que o usuário atual faz parte.
     */
    public function indexMyTeams(Request $request, GetTeamsForUserUseCase $useCase)
    {
        $teams = $useCase->execute($request->user()->id);

        return $this->respondWithSuccess([
            'total' => count($teams ?? []),
            'data' => TeamResource::collection($teams)
        ]);
    }


    /**
     * Remover membro da equipe
     *
     * @status 204
     *
     * Remove um usuário de uma equipe específica, se válido.
     */
    public function remove(string $teamId, string $userId, RemoveTeamMemberUseCase $useCase)
    {
        try {
            $useCase->execute($teamId, $userId);
            return $this->respondNoContent();
        } catch (InvalidTeamMemberException $e) {
            return $this->respondFailedValidation($e->getMessage(), 'error');
        }
    }

}
