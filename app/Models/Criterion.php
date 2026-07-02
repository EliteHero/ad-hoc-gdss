<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Comparison;
use App\Models\Rating;

class Criterion extends Model
{
    protected $fillable = [
        'session_id',
        'name',
        'type',
        'is_fixed',
        'fixed_value'
    ];

    public function decisionSession()
    {
        return $this->belongsTo(DecisionSession::class);
    }

    public function comparisonsAsA()
    {
        return $this->hasMany(Comparison::class, 'criterion_a_id');
    }

    public function comparisonsAsB()
    {
        return $this->hasMany(Comparison::class, 'criterion_b_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
