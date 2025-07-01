<?php

use Audit\Interface\Http\Requests\V1\AuditLogFilterRequest;
use Illuminate\Support\Facades\Validator;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Uid\Ulid;

uses(Tests\TestCase::class, RefreshDatabase::class);

// helper para validar o FormRequest manualmente
function validateAudit(array $input): \Illuminate\Contracts\Validation\Validator
{
    $request = new AuditLogFilterRequest();
    $request->setContainer(app())->setRedirector(app('redirect'));
    $request->merge($input);

    return Validator::make($input, $request->rules(), $request->messages());
}


it('retorna mensagem personalizada para log_name inválido', function () {
    $validator = validateAudit(['log_name' => 'other']);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('log_name'))->toBe('O tipo de log deve ser "task" ou "team".');
});

it('retorna mensagem personalizada para user_id inexistente', function () {
    $validator = validateAudit(['user_id' => Ulid::generate()]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('user_id'))->toBe('O usuário selecionado não foi encontrado.');
});

it('retorna mensagem personalizada para user_id não numérico', function () {
    $validator = validateAudit(['user_id' => 'abc']);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('user_id'))->toBe('A ID do usuário deve ser uma ULID.');
});

it('retorna mensagem personalizada para data inválida', function () {
    $validator = validateAudit(['date' => 'not-a-date']);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('date'))->toBe('A data fornecida não é válida. Use o formato YYYY-MM-DD.');
});

it('valida com sucesso quando os dados estão corretos', function () {
    $user = User::factory()->create();

    $validator = validateAudit([
        'log_name' => 'task',
        'user_id' => $user->id,
        'date' => now()->toDateString(),
    ]);

    expect($validator->fails())->toBeFalse();
});
