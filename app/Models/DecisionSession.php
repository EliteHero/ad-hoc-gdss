<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Criterion;
use App\Models\Alternative;
use App\Models\Comparison;
use App\Models\Rating;
use App\Models\Result;

class DecisionSession extends Model
{
    protected $fillable = [
        'code',
        'title',
        'creator_name',
        'status'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function criteria()
    {
        return $this->hasMany(Criterion::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }

    public function comparisons()
    {
        return $this->hasMany(Comparison::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
