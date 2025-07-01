<?php

use Auth\Infrastructure\Persistence\Model\User;
use Task\Interface\Http\Requests\V1\TaskStoreRequest;
use Illuminate\Support\Facades\Validator;
use Task\Domain\Enums\TaskStatus;

uses(Tests\TestCase::class);

test('valida TaskStoreRequest com dados válidos', function () {
    $data = [
        'title' => 'Minha Tarefa',
        'description' => 'Descrição da tarefa',
        'due_time' => now()->addDay()->toDateString(),
        'status' => TaskStatus::PENDING->value,
        'taskable_type' => 'User',
        'taskable_id' => User::factory()->create()->id
    ];

    // Simula o input vindo da request
    $request = new TaskStoreRequest();
    $request->merge($data);

    // Valida usando as regras do próprio request
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('TaskStoreRequest falha com taskable_type inválido', function () {
    $data = [
        'title' => 'Teste',
        'description' => '...',
        'due_time' => now()->toDateString(),
        'status' => 'invalid',
        'taskable_type' => 'InvalidType',
        'taskable_id' => '01HXXXXX1234567890ABCDEF',
    ];

    $request = new TaskStoreRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->keys())->toContain('status', 'taskable_type');
});
