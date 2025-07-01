<?php

namespace Auth\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}


    public static function fromRequest(FormRequest $request)
    {
        return new self($request->email, $request->password);
    }
}
