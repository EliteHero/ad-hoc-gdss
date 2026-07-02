<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Comparison;
use App\Models\Rating;

class Participant extends Model
{
    protected $fillable = [
        'session_id',
        'name',
        'has_submitted_comparisons',
        'has_submitted_ratings'
    ];

    public function decisionSession()
    {
        return $this->belongsTo(DecisionSession::class);
    }

    public function comparisons()
    {
        return $this->hasMany(Comparison::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
