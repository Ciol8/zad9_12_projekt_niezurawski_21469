<?php

namespace Tests\Feature;

// 1. DODAJ TĘ LINIĘ:
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // 2. DODAJ TĘ LINIĘ WEWNĄTRZ KLASY:
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}