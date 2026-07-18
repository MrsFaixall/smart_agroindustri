<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_view_dashboard(): void
    {
        $user = new User();
        $user->forceFill([
            'name' => 'Faisal',
            'email' => 'faisal@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        $user->save();

        $response = $this->post('/login', [
            'email' => 'faisal@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Selamat Datang');
    }
}
