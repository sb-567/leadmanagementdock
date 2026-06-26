<?php

// app/Models/FollowUp.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'call_status',
        'remarks',
        'next_followup_date',
        'attempt',
    ];

    protected $casts = [
        'next_followup_date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}