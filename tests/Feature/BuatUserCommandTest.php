<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BuatUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_kasir_account(): void
    {
        $this->artisan('belfinance:buat-user', ['--name' => 'kasir1', '--email' => 'kasir1@example.com', '--role' => 'kasir'])
            ->expectsQuestion('Password (minimal 8 karakter)', 'rahasia123')
            ->assertSuccessful();

        $user = User::where('name', 'kasir1')->sole();
        $this->assertTrue($user->isKasir());
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_rejects_invalid_role_and_short_password(): void
    {
        $this->artisan('belfinance:buat-user', ['--name' => 'x', '--email' => 'x@example.com', '--role' => 'admin'])
            ->expectsQuestion('Password (minimal 8 karakter)', '123')
            ->assertFailed();

        $this->assertDatabaseMissing('users', ['name' => 'x']);
    }
}
