<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\LoginAttempt;
use Spatie\Permission\Models\Permission;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'is_locked',
        'lock_time'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function loginAttempt()
    { 
        return $this->hasOne(LoginAttempt::class); 
    }
     public function bankAccount()
    {
        return $this->hasMany(BankAccount::class);
    }
    public function bankCard()
    {
        return $this->hasOne(bankCard::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

}
