<?php

namespace App\Http\Controllers;

use App\Models\IncomeDetail;
use Illuminate\Http\Request;

class DetailPemasukkanController extends Controller
{
    public function view(){

        return view('keuangan-detail-pemasukkan');
    }
}
