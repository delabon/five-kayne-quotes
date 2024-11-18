<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\User;

trait LoginsUser
{
    public function createUserAndLogin(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        session()->put('auth_token', $token);
        $this->actingAs($user);

        return [
            $user,
            $token
        ];
    }
}
