<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function lyrics()
    {
        return $this->belongsToMany(Lyric::class)->withTimestamps()->withPivot('score', 'level', 'created_at');
    }

    public function lyrics_created()
    {
        return $this->hasMany(Lyric::class, 'user_id');
    }

}
