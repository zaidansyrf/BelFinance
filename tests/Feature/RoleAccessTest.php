<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function halamanKeuangan(): array
    {
        return [
            ['/keuangan/dashboard'],
            ['/keuangan/pembayaran'],
            ['/keuangan/pembayaran/create'],
            ['/keuangan/uang-masuk'],
            ['/keuangan/pengeluaran'],
            ['/keuangan/pengeluaran/create'],
            ['/keuangan/menu'],
            ['/keuangan/kategori/sumber-masuk'],
            ['/keuangan/kategori/sumber-keluar'],
            ['/keuangan/laporan-keuangan'],
        ];
    }

    public static function halamanOwner(): array
    {
        return [
            ['/owner/beranda'],
            ['/owner/laporan'],
            ['/owner/info-profile'],
        ];
    }

    public static function halamanBersama(): array
    {
        return [
            ['/keuangan/info-profile'],
            ['/laporan/export-pdf'],
        ];
    }

    public static function semuaHalaman(): array
    {
        return array_merge(self::halamanKeuangan(), self::halamanOwner(), self::halamanBersama());
    }

    #[DataProvider('semuaHalaman')]
    public function test_guest_is_redirected_to_login(string $url): void
    {
        $this->get($url)->assertRedirect(route('login'));
    }

    #[DataProvider('halamanKeuangan')]
    public function test_keuangan_can_open_keuangan_pages(string $url): void
    {
        $this->actingAs(User::factory()->keuangan()->create())->get($url)->assertOk();
    }

    #[DataProvider('halamanKeuangan')]
    public function test_owner_is_forbidden_from_keuangan_pages(string $url): void
    {
        $this->actingAs(User::factory()->owner()->create())->get($url)->assertForbidden();
    }

    #[DataProvider('halamanOwner')]
    public function test_owner_can_open_owner_pages(string $url): void
    {
        $this->actingAs(User::factory()->owner()->create())->get($url)->assertOk();
    }

    #[DataProvider('halamanOwner')]
    public function test_keuangan_is_forbidden_from_owner_pages(string $url): void
    {
        $this->actingAs(User::factory()->keuangan()->create())->get($url)->assertForbidden();
    }

    #[DataProvider('halamanBersama')]
    public function test_both_roles_can_open_shared_pages(string $url): void
    {
        $this->actingAs(User::factory()->keuangan()->create())->get($url)->assertOk();
        $this->actingAs(User::factory()->owner()->create())->get($url)->assertOk();
    }
}
