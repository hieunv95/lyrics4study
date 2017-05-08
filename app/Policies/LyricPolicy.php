<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lyric;
use Illuminate\Auth\Access\HandlesAuthorization;

class LyricPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the lyric.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lyric  $lyric
     * @return mixed
     */
    public function view(User $user, Lyric $lyric)
    {
        //
    }

    /**
     * Determine whether the user can create lyrics.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the lyric.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lyric  $lyric
     * @return mixed
     */
    public function update(User $user, Lyric $lyric)
    {
        return ($user->id === $lyric->user_id && $lyric->published !== 1);
    }

    /**
     * Determine whether the user can delete the lyric.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lyric  $lyric
     * @return mixed
     */
    public function delete(User $user, Lyric $lyric)
    {
        return ($user->id === $lyric->user_id && $lyric->published !== 1);
    }
}
