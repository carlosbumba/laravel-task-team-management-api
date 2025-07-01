<?php


namespace Task\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Task\Application\UseCases\GetTeamTasksUseCase;
use Task\Domain\Enums\TaskStatus;
use Task\Interface\Http\Resources\V1\TaskResource;
use Team\Infrastructure\Persistence\Model\Team;

use Dedoc\Scramble\Attributes\Group;

#[Group('Equipes')]
class TeamTasksController
{
    use AuthorizesRequests, ApiResponseHelpers;

    /**
     * Listar tarefas de uma equipe
     *
     * @response array{data: TaskResource[]}
     *
     * Retorna todas as tarefas associadas a uma equipe, desde que o usuário tenha permissão para visualizá-la.
     */

    public function __invoke(Request $request, Team $team, GetTeamTasksUseCase $useCase)
    {
        $this->authorize('view', $team);

        $status = $request->query('status');

        if ($status && !in_array($status, TaskStatus::values())) {
            return $this->respondFailedValidation('Invalid status: "' . $status . '" (allowed: ' . implode(', ', TaskStatus::values()) . ')');
        }

        return TaskResource::collection(
            $useCase->execute(Team::class, $team->id, $status)
        );
    }
}
