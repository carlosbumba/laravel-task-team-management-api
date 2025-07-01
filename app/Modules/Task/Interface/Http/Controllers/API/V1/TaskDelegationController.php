<?php

namespace Task\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Task\Application\DTOs\CreateTaskDTO;
use Task\Application\UseCases\DelegateTaskUseCase;
use Task\Interface\Http\Requests\V1\TaskStoreRequest;
use Task\Interface\Http\Resources\V1\TaskResource;

use Dedoc\Scramble\Attributes\Group;

#[Group('Tarefas')]
class TaskDelegationController
{
    use AuthorizesRequests, ApiResponseHelpers;

    /**
     * Delegar tarefa
     *
     * @response array{data: TaskResource}
     * @status 201
     *
     * Permite criar uma tarefa delegada para outro usuário ou equipe, conforme permissões do usuário autenticado.
     */

    public function __invoke(TaskStoreRequest $request, DelegateTaskUseCase $useCase)
    {
        $this->authorize('delegate', \Task\Infrastructure\Persistence\Model\Task::class);

        try {
            $dto = CreateTaskDTO::fromRequest($request, $request->user()->id);

            $task = $useCase->execute($dto);

            return $this->respondCreated(['data' => new TaskResource($task)]);
        } catch (\DomainException $e) {
            return $this->respondForbidden($e->getMessage());
        } catch (\InvalidArgumentException $th) {
            return $this->respondError($th->getMessage());
        }
    }
}
