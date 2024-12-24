<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UangMasukController extends Controller
{
    public function index()
    {
        return view('keuangan-uang-masuk');
    }

}
