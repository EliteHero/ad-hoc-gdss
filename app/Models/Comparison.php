<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Participant;
use App\Models\Criterion;

class Comparison extends Model
{
    protected $fillable = [
        'session_id',
        'participant_id',
        'criterion_a_id',
        'criterion_b_id',
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
    
    public function criterionA()
    {
        return $this->belongsTo(Criterion::class, 'criterion_a_id');
    }

    public function criterionB()
    {
        return $this->belongsTo(Criterion::class, 'criterion_b_id');
    }
}