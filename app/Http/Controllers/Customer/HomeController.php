<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('status', true)->get();
        return view('customer.home', compact('promotions'));
    }
}
