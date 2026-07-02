<?php

namespace App\Http\Controllers;

use App\Models\DecisionSession;

class SessionController extends Controller
{
    public function index()
    {
        return view('sessions.index');
    }
}