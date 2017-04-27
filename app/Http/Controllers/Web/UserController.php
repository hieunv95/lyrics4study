<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class UserController extends Controller
{
    public function history()
    {
        return view('web.user.history')->with([
            'lyrics' => paginateCollection(Auth::user()->lyrics()->get()->groupBy('id'), 10),
        ]);
    }

    public function detailHistory($lyricId)
    {
        return view('web.user.detail_history')->with([
            'lyrics' => Auth::user()->lyrics()->where('lyrics.id', $lyricId)->paginate(20),
        ]);
    }

    public function showLyrics($userId)
    {
        $user = User::findOrFail($userId);
        return view('web.user.show_lyrics')->with([
            'list' => $user->lyrics_created()->paginate(20),
            'userName' => $user->name,
        ]);
    }

    public function saveScore(Request $req)
    {
        $level = $req->input('level');
        $lyricId = $req->input('lyric_id');
        $score = $req->input('score');

        DB::beginTransaction();
        try {
            if (Auth::check()) {
                Auth::user()->lyrics()->attach($lyricId, [
                    'score' => $score,
                    'level' => $level,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response('Submit failed', 500);
        }

        return response('Submit success', 200);
    }
}
