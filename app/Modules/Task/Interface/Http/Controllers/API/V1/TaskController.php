<?php

namespace Task\Interface\Http\Controllers\API\V1;

use F9Web\ApiResponseHelpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Task\Application\DTOs\CreateOwnTaskDTO;
use Task\Application\DTOs\UpdateTaskDTO;
use Task\Application\UseCases\CreateOwnTaskUseCase;
use Task\Application\UseCases\DeleteTaskUseCase;
use Task\Application\UseCases\GetTaskByIdUseCase;
use Task\Domain\Enums\TaskStatus;
use Task\Interface\Http\Requests\V1\TaskStoreRequest;
use Task\Interface\Http\Requests\V1\TaskUpdateRequest;
use Task\Interface\Http\Resources\V1\TaskResource;
use Task\Infrastructure\Persistence\Model\Task;
use Task\Application\UseCases\GetTasksByUserUseCase;
use Task\Application\UseCases\UpdateTaskUseCase;

use Dedoc\Scramble\Attributes\Group;


#[Group('Tarefas')]
class TaskController
{
    use AuthorizesRequests, ApiResponseHelpers;

    /**
     * Listar tarefas do usuário
     *
     * @response array{data: TaskResource[]}
     *
     * Retorna todas as tarefas do usuário autenticado. É possível filtrar por status.
     */

    public function index(Request $request, GetTasksByUserUseCase $useCase)
    {
        $status = $request->query('status');

        if ($status && !in_array($status, TaskStatus::values())) {
            return $this->respondFailedValidation('Invalid status: "' . $status . '" (allowed: ' . implode(', ', TaskStatus::values()) . ')');
        }

        $tasks = $useCase->execute($request->user()->id, $status);

        return TaskResource::collection($tasks);
    }

    /**
     * Criar nova tarefa
     *
     * @status 201
     * @response array{data: TaskResource}
     *
     * Cria uma nova tarefa pessoal vinculada ao usuário autenticado.
     */

    public function store(TaskStoreRequest $request, CreateOwnTaskUseCase $useCase)
    {
        $user = $request->user();

        $dto = CreateOwnTaskDTO::fromRequest($request, $user::class, $user->id);

        return $this->respondCreated([
            'data' => new TaskResource($useCase->execute($dto))
        ]);
    }

    /**
     * Visualizar tarefa
     *
     * @response array{data: TaskResource}
     *
     * Exibe os detalhes de uma tarefa específica se o usuário tiver permissão.
     */

    public function show(Task $task, GetTaskByIdUseCase $useCase)
    {
        $this->authorize('view', $task);

        return $this->respondWithSuccess([
            'data' => new TaskResource($useCase->execute($task->id))
        ]);
    }

    /**
     * Atualizar tarefa
     *
     * @response array{data: TaskResource}
     *
     * Atualiza os dados de uma tarefa existente se o usuário for autorizado.
     */

    public function update(TaskUpdateRequest $request, Task $task, UpdateTaskUseCase $useCase)
    {
        $this->authorize('update', $task);

        $dto = UpdateTaskDTO::fromModelWithRequest($task, $request);

        return $this->respondWithSuccess([
            'data' => new TaskResource($useCase->execute($dto))
        ]);
    }

    /**
     * Deletar tarefa
     *
     * @status 204
     *
     * Remove uma tarefa da base de dados, se o usuário tiver permissão.
     */

    public function destroy(Task $task, DeleteTaskUseCase $useCase)
    {
        $this->authorize('delete', $task);
        $useCase->execute($task->id);

        return $this->respondNoContent();
    }
}
