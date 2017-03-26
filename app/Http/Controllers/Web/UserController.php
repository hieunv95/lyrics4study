<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
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

    public function detailHistory($lyric_id)
    {
        return view('web.user.detail_history')->with([
            'lyrics' => Auth::user()->lyrics()->where('lyrics.id', $lyric_id)->paginate(20),
        ]);
    }
}
