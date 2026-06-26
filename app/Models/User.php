<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function getDashboardRoute(): string
    {
        if ($this->hasRole('admin')) {
            return route('admin.dashboard');
        } elseif ($this->hasRole('teamleader')) {
            return route('tl.dashboard');
        }
        return route('telecaller.dashboard');
    }

    // Relationships (we'll add leads here next)
    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}