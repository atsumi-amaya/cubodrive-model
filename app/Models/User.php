<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "users";
    protected $fillable = [
        'username',
        'email',
        'email_verified_at',
        'password',
        'rol',
        'lider',
        'remember_token',
        'pre_auth',
        'pre_auth_id',
        'pre_auth_expire',
        'plan',
        'uso',
        'limite',
        'created_at', 
        'updated_at'];
    protected $hidden = [
        'id',
        'password',
        'remember_token'];
    protected $casts = [
            'email_verified_at' => 'datetime',
        ];
    protected $primaryKey = 'id';
}
