<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Eloquent;

class Lyric extends Eloquent
{
    use Searchable;

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDayDateTimeString();
    }
}
