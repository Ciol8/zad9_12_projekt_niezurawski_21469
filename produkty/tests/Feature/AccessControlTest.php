<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_access_admin_panel()
    {
        // 1. Tworzymy klienta
        $client = User::factory()->create(['role' => 'client']);

        // 2. Logujemy się jako klient i próbujemy wejść w użytkowników
        $response = $this->actingAs($client)->get(route('admin.users.index'));

        // 3. Oczekujemy błędu 403 (Forbidden - Zabronione)
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_panel()
    {
        // 1. Tworzymy admina
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Wchodzimy
        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        // 3. Oczekujemy sukcesu 200 OK
        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_login()
    {
        // 1. Niezalogowany wchodzi na panel admina
        $response = $this->get(route('admin.users.index'));

        // 2. Oczekujemy przekierowania (302) do logowania
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}