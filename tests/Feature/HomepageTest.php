<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\LoginsUser;

class HomepageTest extends TestCase
{
    use RefreshDatabase;
    use LoginsUser;

    #[Test]
    public function returns_correct_view(): void
    {
        /** @var User $user */
        [$user, $token] = $this->createUserAndLogin();

        // Mock the external API request (e.g., /api/quotes) made by fetchQuotes
        // Because session-based authentication (via actingAs()) will not affect external HTTP requests in feature tests by default
        Http::fake([
            config('app.url') . '/api/quotes' => Http::response(['quotes' => []], 200),
        ]);

        // Acting as the created user
        $this->actingAs($user)
            ->withSession([
                'auth_token' => $token
            ])
            ->get('/') // This triggers the fetchQuotes method
            ->assertOk()
            ->assertViewIs('welcome');

        Http::assertSent(function ($request) use ($token) {
            return $request->hasHeader('Authorization', 'Bearer ' . $token);
        });
    }

    #[Test]
    public function redirects_to_login_page_when_not_authenticated(): void
    {
        $this->get('/')
            ->assertRedirectToRoute('login');
    }
}
