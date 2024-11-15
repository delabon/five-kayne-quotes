<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function returns_correct_view(): void
    {
        $this->get('/login')
            ->assertViewIs('auth.login')
            ->assertOk();
    }

    #[Test]
    public function logins_user_successfully(): void
    {
        $credentials = [
            'email' => 'john@test.com',
            'password' => '12345'
        ];
        $user = User::factory()->create(
            array_merge($credentials, [
                'name' => 'john'
            ])
        );

        $this->post('/login', $credentials)
            ->assertRedirectToRoute('home')
            ->assertSessionHas('success', 'You have signed-in successfully.');

        $this->assertSame($user->id, auth()->user()->id);
        $this->assertSame($user->email, auth()->user()->email);
    }

    #[Test]
    public function fails_when_incorrect_credentials(): void
    {
        $credentials = [
            'email' => 'john@test.com',
            'password' => '12345'
        ];

        $this->post('/login', $credentials)
            ->assertRedirectToRoute('login')
            ->assertSessionHasErrors([
                'email' => 'Invalid login credentials.'
            ]);

        $this->assertNull(auth()->user());
    }
}
