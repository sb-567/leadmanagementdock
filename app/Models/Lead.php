<?php

// app/Models/Lead.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'product_interest',
        'city',
        'remarks',
        'status',
        'assigned_to',
        'created_by',
    ];
    

    // status label with color for blade
    public static array $statuses = [
        'new'            => ['label' => 'New',            'color' => 'primary'],
        'interested'     => ['label' => 'Interested',     'color' => 'info'],
        'not_interested' => ['label' => 'Not Interested', 'color' => 'secondary'],
        'callback'       => ['label' => 'Callback',       'color' => 'warning'],
        'converted'      => ['label' => 'Converted',      'color' => 'success'],
        'junk'           => ['label' => 'Junk',           'color' => 'danger'],
    ];

    public function statusLabel(): string
    {
        return self::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function statusColor(): string
    {
        return self::$statuses[$this->status]['color'] ?? 'secondary';
    }

    // Relationships
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function latestFollowUp()
    {
        return $this->hasOne(FollowUp::class)->latestOfMany();
    }
}