<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Alternative;

class Result extends Model
{
    protected $fillable = [
        'session_id',
        'alternative_id',
        'rank',
        'score'
    ];

    public function decisionSession()
    {
        return $this->belongsTo(DecisionSession::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}