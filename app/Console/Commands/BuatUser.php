<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * Membuat akun baru (keuangan, owner, atau kasir) karena registrasi publik dimatikan.
 *
 * Contoh: php artisan belfinance:buat-user --role=kasir
 */
class BuatUser extends Command
{
    protected $signature = 'belfinance:buat-user
        {--name= : Username untuk login}
        {--email= : Email akun}
        {--role= : keuangan, owner, atau kasir}';

    protected $description = 'Buat akun BelFinance baru (keuangan, owner, atau kasir)';

    public function handle(): int
    {
        $data = [
            'name' => $this->option('name') ?: text('Username (dipakai untuk login)', required: true),
            'email' => $this->option('email') ?: text('Email', required: true),
            'role' => $this->option('role') ?: select('Role', User::ROLES, default: User::ROLE_KASIR),
            'password' => password('Password (minimal 8 karakter)', required: true),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => ['required', Password::min(8)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create($data + ['email_verified_at' => now()]);

        $this->info("Akun {$user->name} ({$user->role}) berhasil dibuat.");

        return self::SUCCESS;
    }
}
