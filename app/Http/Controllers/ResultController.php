<?php

namespace App\Http\Controllers;

use App\Models\DecisionSession;

class ResultController extends Controller
{
    public function index($code)
    {
        $session = DecisionSession::where('code', $code)->firstOrFail();
        return view('sessions.results', compact('session'));
    }
}