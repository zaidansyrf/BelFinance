<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Source;

class UangMasukController extends Controller
{
    public function index()
    {
        $income = Income::with(['source'])->get();
        return view('keuangan-uang-masuk', compact('income'));
    }

}
