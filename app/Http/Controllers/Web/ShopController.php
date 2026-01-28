<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::where('status', 'active')->latest()->get();
        return view('shop', compact('products'));
    }
}
