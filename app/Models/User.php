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
    protected $primaryKey = 'uid';   // <--- Tell Laravel your PK is uid
    public $incrementing = false;    // <--- uid is not auto-increment
    protected $keyType = 'string';   // <--- or 'int' if uid is integer

    protected $fillable = [
        'uid',
        'full_name',
        'pass',
    ];

   protected $hidden = [
        'pass',
    ];

    public $timestamps = false;
}
