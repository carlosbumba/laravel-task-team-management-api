<?php

use Task\Interface\Http\Requests\V1\TaskUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Task\Domain\Enums\TaskStatus;

uses(Tests\TestCase::class);

test('TaskUpdateRequest aceita campos válidos', function () {
    $data = [
        'title' => 'Atualizar tarefa',
        'description' => 'Descrição atualizada',
        'due_time' => now()->addDay()->toDateString(),
        'status' => TaskStatus::COMPLETED->value,
    ];

    $request = new TaskUpdateRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('TaskUpdateRequest falha com status inválido', function () {
    $data = [
        'status' => 'INVALID_STATUS',
    ];

    $request = new TaskUpdateRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->keys())->toContain('status');
});

test('TaskUpdateRequest permite requisição vazia (nenhum campo)', function () {
    $data = [];

    $request = new TaskUpdateRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});
