<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function returns_correct_view(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/')
            ->assertStatus(200)
            ->assertViewIs('welcome');
    }

    #[Test]
    public function redirects_to_login_page_when_not_authenticated(): void
    {
        $this->get('/')
            ->assertRedirectToRoute('login');
    }
}
