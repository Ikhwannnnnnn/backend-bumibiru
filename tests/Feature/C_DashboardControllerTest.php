<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class C_DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
        $this->withoutMiddleware('login');
    }

    public function testMentorCanViewDashboard()
    {
        $user = User::factory()->create(['role' => 'mentor']);
        Mentor::factory()->create(['id_user' => $user->id]);

        session(['user' => ['role' => 'mentor', 'id' => $user->id]]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard_mentor');
    }


    public function testAdminCanViewDashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);

        session(['user' => ['role' => 'admin', 'id' => $user->id]]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard_admin');
    }

    public function testResetPasswordWithValidData()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $email = encrypt('test@example.com');

        $response = $this->post(route('resetPass', ['email' => $email]), [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertRedirect('/');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function testResetPasswordWithInvalidData()
    {
        $email = encrypt('nonexistent@example.com');

        $response = $this->post(route('resetPass', ['email' => $email]), [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertRedirect('/');
    }

    public function testUpdateEmailWithValidData()
    {
        $user = User::factory()->create(['email' => 'old@example.com']);
        $email = encrypt('old@example.com');

        $response = $this->post(route('updateEmail', ['email' => $email]), [
            'email' => 'new@example.com'
        ]);

        $response->assertRedirect('/');
        $this->assertEquals('new@example.com', $user->fresh()->email);
    }

    public function testUpdateEmailWithInvalidData()
    {
        $user = User::factory()->create(['email' => 'old@example.com']);
        $email = encrypt('old@example.com');

        $response = $this->post(route('updateEmail', ['email' => $email]), [
            'email' => 'invalid-email'
        ]);

        $response->assertRedirect('/');
        // $response->assertSessionHasErrors();
    }
}
