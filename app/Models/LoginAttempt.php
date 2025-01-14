<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LoginAttempt extends Model
{
        protected $table = 'login_attempts';
    
        protected $fillable = [
            'user_id',
            'attempts',
            'locked_until',
        ];
    
        protected $casts = [
            'locked_until' => 'datetime',
        ];
    
        public function user()
        {
            return $this->belongsTo(User::class);
        }
}
