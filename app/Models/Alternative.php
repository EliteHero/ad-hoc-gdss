<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DecisionSession;
use App\Models\Rating;

class Alternative extends Model
{
    protected $fillable = [
        'session_id',
        'name'
    ];

    public function decisionSession()
    {
        return $this->belongsTo(DecisionSession::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
