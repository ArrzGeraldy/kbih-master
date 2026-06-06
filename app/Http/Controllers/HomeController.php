<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $pakets = Paket::query()->latest()->get();

        return view('index', compact('pakets'));
    }
}
