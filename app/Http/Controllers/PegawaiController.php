<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Owner mengelola akun pegawai (kasir dan keuangan). Akun owner tidak bisa dibuat atau diubah di sini.
 */
class PegawaiController extends Controller
{
    private const ROLE_PEGAWAI = [User::ROLE_KASIR, User::ROLE_KEUANGAN];

    public function index()
    {
        return view('pegawai.index', [
            'pegawai' => User::whereIn('role', self::ROLE_PEGAWAI)->orderBy('role')->orderBy('name')->get(),
            'roles' => self::ROLE_PEGAWAI,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(self::ROLE_PEGAWAI)],
            'password' => ['required', Password::min(8)],
        ]);

        User::create($data + ['email_verified_at' => now()]);

        return redirect()->route('pegawai.index')->with('success', "Akun {$data['name']} berhasil dibuat.");
    }

    public function update(Request $request, User $pegawai)
    {
        $this->pastikanPegawai($pegawai);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($pegawai->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pegawai->id)],
            'role' => ['required', Rule::in(self::ROLE_PEGAWAI)],
            'password' => ['nullable', Password::min(8)],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('success', "Akun {$pegawai->name} diperbarui.");
    }

    public function destroy(User $pegawai)
    {
        $this->pastikanPegawai($pegawai);

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', "Akun {$pegawai->name} dihapus.");
    }

    private function pastikanPegawai(User $user): void
    {
        abort_unless(in_array($user->role, self::ROLE_PEGAWAI, true), 404);
    }
}
