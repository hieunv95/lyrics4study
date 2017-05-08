<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Eloquent;

class Lyric extends Eloquent
{
    use Searchable;

    protected $fillable = [
        'title',
        'artist',
        'link_id',
        'lyric',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDayDateTimeString();
    }
}
