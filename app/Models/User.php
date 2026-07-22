<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminRole(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function isContentEditor(): bool
    {
        return $this->role === 'content_editor';
    }

    public function isReception(): bool
    {
        return $this->role === 'reception';
    }

    public function hasRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : func_get_args();
        return in_array($this->role, $roles);
    }

    public function hasModuleAccess(string $module): bool
    {
        if (in_array($this->role, ['super_admin', 'admin'])) {
            return true;
        }

        if ($this->role === 'hr') {
            return in_array($module, ['dashboard', 'careers', 'applications', 'departments', 'employment-types']);
        }

        if ($this->role === 'content_editor') {
            return in_array($module, ['dashboard', 'blogs', 'blog-categories', 'blog-tags', 'blog-authors']);
        }

        if ($this->role === 'reception') {
            return in_array($module, ['dashboard', 'appointments', 'memberships']);
        }

        return false;
    }
}
