<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Participant;
use App\Models\Alternative;
use App\Models\Criterion;

class Rating extends Model
{
    protected $fillable = [
        'session_id',
        'participant_id',
        'alternative_id',
        'criterion_id',
        'value'
    ];

    public function decisionSession()
    {
        return $this->belongsTo(DecisionSession::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
