<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\A2CRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class A2CController extends Controller
{
    public function index()
    {
        return view('a2c.index');
    }
}
