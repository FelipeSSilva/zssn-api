<?php

namespace App\Http\Controllers;

use App\Survivor;
use Illuminate\Http\Request;

class SurvivorsController extends Controller
{
    public function index()
    {
        $survivors = Survivor::get();
        return response()->json($survivors);
    }
}
