<?php

namespace Audit\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AuditLogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'log_name' => ['nullable', 'string', 'in:task,team'],
            'user_id' => ['nullable', 'ulid', 'exists:users,id'],
            'date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'log_name.in' => 'O tipo de log deve ser "task" ou "team".',
            'log_name.string' => 'O tipo de log deve ser uma string válida.',

            'user_id.ulid' => 'A ID do usuário deve ser uma ULID.',
            'user_id.exists' => 'O usuário selecionado não foi encontrado.',

            'date.date' => 'A data fornecida não é válida. Use o formato YYYY-MM-DD.',
        ];
    }
}
