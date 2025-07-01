<?php

namespace Auth\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRegisterDTO
{
    public function __construct(
        public string $name,
        public string $role,
        public string $email,
        public string $password
    ) {
        $this->hashPassword();
    }

    public static function fromRequest(FormRequest $request): UserRegisterDTO
    {
        return new self($request->name, $request->role, $request->email, $request->password);
    }

    private function hashPassword()
    {
        $this->password = Hash::make($this->password);
    }
}
