<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function history()
    {
        return view('web.user.history')->with([
            'lyrics' => Auth::user()->lyrics()->paginate(2),
        ]);
    }
}
