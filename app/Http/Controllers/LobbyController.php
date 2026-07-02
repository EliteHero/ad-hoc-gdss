<?php

namespace App\Http\Controllers;

use App\Models\DecisionSession;

class LobbyController extends Controller
{
    public function index($code)
    {
        $session = DecisionSession::where('code', $code)->firstOrFail();
        return view('sessions.lobby', compact('session'));
    }
}