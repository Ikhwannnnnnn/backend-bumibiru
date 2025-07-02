<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class C_AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginViewCanBeRendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!')
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'Password123!'
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!')
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['error' => 'Akun Tidak Terdaftar']);
        $this->assertGuest();
    }

    public function testUserCanRegister()
    {
        $response = $this->post('/register', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com'
        ]);

        $this->assertDatabaseHas('mentors', [
            'id_user' => User::where('email', 'newuser@example.com')->first()->id
        ]);
    }

    public function testUserCannotRegisterWithInvalidData()
    {
        $response = $this->post('/register', [
            'username' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'short'
        ]);

        $response->assertRedirect('/');
        $this->assertEquals(session('error'), 'Username harus diisi.');
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email'
        ]);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('logout'));
        $response->assertRedirect(route('login'));
    }


    public function testForgotPasswordViewCanBeRendered()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot_password');
    }

    public function testUserCanResetPassword()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('OldPassword123!')
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'user@example.com',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertRedirect('/');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function testUserCannotResetPasswordWithInvalidEmail()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['error' => 'Email tidak ditemukan.']);
    }
}
