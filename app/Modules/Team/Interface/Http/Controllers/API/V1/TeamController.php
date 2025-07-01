<?php

namespace Team\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Team\Application\DTOs\TeamDTO;
use Team\Application\UseCases\CreateTeamCase;
use Team\Application\UseCases\DeleteTeamUseCase;
use Team\Application\UseCases\GetAllTeamsUseCase;
use Team\Application\UseCases\GetTeamByIdUseCase;
use Team\Application\UseCases\UpdateTeamUseCase;
use Team\Infrastructure\Persistence\Model\Team;
use Team\Interface\Http\Requests\V1\TeamCreateRequest;
use Team\Interface\Http\Requests\V1\TeamUpdadeRequest;
use Team\Interface\Http\Resources\V1\TeamResource;

use Dedoc\Scramble\Attributes\Group;

#[Group('Equipes')]
class TeamController
{
    use AuthorizesRequests, ApiResponseHelpers;

    /**
     * Listar equipes do usuário
     *
     * @response array{data: TeamResource[]}
     *
     * Retorna todas as equipes criadas.
     */
    public function index(GetAllTeamsUseCase $useCase)
    {
        $this->authorize('viewAny', Team::class);

        return TeamResource::collection($useCase->execute());
    }

    /**
     * Criar nova equipe
     *
     * @response array{data: TeamResource}
     * @status 201
     *
     * Cria uma nova equipe se o usuário estiver autorizado.
     */
    public function store(TeamCreateRequest $request, CreateTeamCase $userCase)
    {
        $this->authorize('create', Team::class);

        $dto = TeamDTO::fromRequest($request);

        return $this->respondCreated(['data' => new TeamResource($userCase->execute($dto))]);
    }

    /**
     * Visualizar equipe
     *
     * @response array{data: TeamResource}
     *
     * Retorna os detalhes de uma equipe específica se o usuário estiver autorizado.
     */
    public function show(Team $team, GetTeamByIdUseCase $useCase)
    {
        $this->authorize('view', $team);

        // nao houve necessidade do caso de uso, devido a simplicidade da acao, mas este existe
        // para que no futuro seja flexivel adicionar acoes extras
        return $this->respondWithSuccess(['data' => new TeamResource($team)]);
    }

    /**
     * Atualizar equipe
     *
     * @response array{data: TeamResource}
     *
     * Atualiza os dados de uma equipe existente, desde que o usuário tenha permissão.
     */
    public function update(TeamUpdadeRequest $request, string $id, UpdateTeamUseCase $useCase)
    {
        $this->authorize('update', Team::class);

        return $this->respondWithSuccess(['data' => $useCase->execute(TeamDTO::fromRequest($request, $id))]);
    }

    /**
     * Deletar equipe
     *
     * @status 204
     *
     * Remove uma equipe do sistema se o usuário for autorizado a gerenciá-la.
     */
    public function destroy(string $id, DeleteTeamUseCase $useCase)
    {
        $this->authorize('delete', Team::class);

        $useCase->execute($id);

        return $this->respondNoContent();
    }
}
