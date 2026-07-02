<?php

namespace App\Http\Controllers;

use App\Models\DecisionSession;

class SetupController extends Controller
{
    public function index($code)
    {
        $session = DecisionSession::where('code', $code)->firstOrFail();
        return view('sessions.setup', compact('session'));
    }
}