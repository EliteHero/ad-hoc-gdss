<?php

namespace App\Http\Controllers;

use App\Models\DecisionSession;

class RatingController extends Controller
{
    public function index($code)
    {
        $session = DecisionSession::where('code', $code)->firstOrFail();
        return view('sessions.rate', compact('session'));
    }
}