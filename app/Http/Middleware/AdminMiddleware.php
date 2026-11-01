<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;



class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Contoh pemakaian di route: ->middleware('role:keuangan') atau 'role:keuangan,owner'
     * Owner punya akses ke semua halaman, jadi lolos dari pengecekan role mana pun.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Periksa apakah role user termasuk role yang diizinkan
        if (!Auth::check() || !(Auth::user()->isOwner() || in_array(Auth::user()->role, $roles, true))) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }




}
