<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    #[Test]
    public function returns_correct_view(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertViewIs('welcome');
    }
}
