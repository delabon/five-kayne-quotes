<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
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
        session()->start();
        $sessionIdBefore = session()->id();

        $credentials = [
            'email' => 'john@test.com',
            'password' => '12345'
        ];
        $user = User::factory()->create(
            array_merge($credentials, [
                'name' => 'john'
            ])
        );

        $response = $this->post('/login', $credentials)
            ->assertRedirectToRoute('home')
            ->assertSessionHas('success', 'You have signed-in successfully.');

        $this->assertSame($user->id, auth()->user()->id);
        $this->assertSame($user->email, auth()->user()->email);

        // Make sure the session is regenerated after login
        $this->assertNotSame($sessionIdBefore, session()->id());

        // Make sure a new token is generated
        $response->assertSessionHas('token');
    }

    #[Test]
    #[DataProvider('invalidCredentialsDataProvider')]
    public function fails_when(array $credentials, array $errors): void
    {
        $this->post('/login', $credentials)
            ->assertSessionHasErrors($errors);

        $this->assertNull(auth()->user());
    }

    public static function invalidCredentialsDataProvider(): array
    {
        return [
            'Account does not exist' => [
                'credentials' => [
                    'email' => 'john@test.com',
                    'password' => '12345',
                ],
                'errors' => [
                    'email' => 'Invalid login credentials.'
                ]
            ],
            'No email' => [
                'credentials' => [
                    'password' => '12345',
                ],
                'errors' => [
                    'email' => 'The email field is required.'
                ],
            ],
            'Invalid email' => [
                'credentials' => [
                    'email' => 12345143,
                    'password' => '12345',
                ],
                'errors' => [
                    'email' => 'The email field must be a valid email address.'
                ],
            ],
            'No password' => [
                'credentials' => [
                    'email' => 'john@test.com',
                ],
                'errors' => [
                    'password' => 'The password field is required.'
                ],
            ],
            'Invalid password' => [
                'credentials' => [
                    'email' => 'john@test.com',
                    'password' => 1344214
                ],
                'errors' => [
                    'password' => 'The password field must be a string.'
                ],
            ],
        ];
    }
}
