<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InfoProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_change_requires_correct_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('info-profile'))
            ->post(route('info-profile.update-password'), [
                'current_password' => 'salah',
                'new_password' => 'passwordbaru123',
                'new_password_confirmation' => 'passwordbaru123',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_password_can_be_changed_with_correct_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('info-profile'))
            ->post(route('info-profile.update-password'), [
                'current_password' => 'password',
                'new_password' => 'passwordbaru123',
                'new_password_confirmation' => 'passwordbaru123',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('info-profile'));

        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
    }

    public function test_profile_name_and_email_can_be_updated(): void
    {
        $user = User::factory()->owner()->create();

        $this->actingAs($user)
            ->from(route('info-profile'))
            ->post(route('info-profile.update'), [
                'name' => 'owner-baru',
                'email' => 'owner-baru@example.com',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('owner-baru', $user->fresh()->name);
    }
}
