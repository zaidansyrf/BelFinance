<?php

namespace App\Http\Controllers;

use App\Services\SaranUsahaService;

class SaranUsahaController extends Controller
{
    public function index(SaranUsahaService $service)
    {
        return view('saran.index', $service->analisis());
    }
}
