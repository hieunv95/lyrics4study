<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Lyric;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $topLyrics = Lyric::orderBy('viewed', 'desc')->take(3)->get();
        $newLyrics = Lyric::orderBy('created_at', 'desc')->take(8)->get();
        $slideNumber = ceil(count($newLyrics) / 4);
        $firstSlideLyrics = array();
        $j = 1;
        foreach ($newLyrics as $key => $value) {
            if ($j > 4) {
                break;
            }
            $firstSlideLyrics[] = $key;
            $j++;
        }

        return view('web.home')->with([
            'topLyrics' => $topLyrics,
            'newLyrics' => $newLyrics,
            'slideNumber' => $slideNumber,
            'firstSlideLyrics' => $firstSlideLyrics,
        ]);
    }

    public function showTopLyrics() {
        return view('web.top_lyrics')->with([
            'lyrics' => Lyric::orderBy('viewed', 'desc')->paginate(config('custom.paginate.lyrics')),
        ]);
    }

    public function showNewLyrics() {
        return view('web.new_lyrics')->with([
            'lyrics' => Lyric::orderBy('created_at', 'desc')->paginate(config('custom.paginate.lyrics')),
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('q');
        if ($search !== "") {
            $search_param = $request->input('search_param');
            if ($search_param === 'artist') {
                $results = Lyric::where('artist', 'LIKE', "%$search%")
                    //->where('published', 1)
                    ->paginate(config('custom.paginate.lyrics'));
            } elseif ($search_param === 'title') {
                $results = Lyric::where('title', 'LIKE', "%$search%")
                    //->where('published', 1)
                    ->paginate(config('custom.paginate.lyrics'));
            } elseif ($search_param === 'lyrics') {
                /*$results = Lyric::search($search)
                    //->where('published', 1)
                    ->paginate(config('custom.paginate.lyrics'));*/
                $results = paginateCollection(Lyric::search($search)->get(), config('custom.paginate.lyrics'));
            } else {
                /*$results = Lyric::search($search)
                    //->where('published', 1)
                    ->paginate(config('custom.paginate.lyrics'));*/
                $results = paginateCollection(Lyric::search($search)->get(), config('custom.paginate.lyrics'));
            }
        } else {
            $results = paginateCollection(collect([]));
        }

        return view('web.search')->with([
            'lyrics' => $results,
            'search' => $search,
        ]);
    }

    public function autocomplete(Request $request)
    {
        //$term which jquery ui generated contains words user type
        $term = $request->input('term');
        /*$lyrics = Lyric::where('title', 'LIKE', "%$term%")
            ->orWhere('artist', 'LIKE', "%$term%")
            //->where('published', 1)
            ->take(5)
            ->get();*/
        $lyrics = Lyric::search($term)->take(10)->get();
        $results = array();
        foreach ($lyrics as $lyric) {
            $results[] = [
                'id' => $lyric->id,
                'value' => $lyric->title . ' - '. $lyric->artist,
                'href' => action('Web\LyricController@show', [
                    'artist' => $lyric->artist,
                    'title' => $lyric->title,
                    'id' => $lyric->id,
                ]),
            ];
        }

        return Response::json($results);
    }
}
