<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Lyric;
use Illuminate\Support\Facades\Response;
use Request;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $top_lyrics = Lyric::paginate(3);
        $new_lyrics = Lyric::orderBy('created_at', 'desc')->take(8)->get();
        return view('web.home')->with([
            't_lyrics' => $top_lyrics,
            'n_lyrics' => $new_lyrics,
        ]);
    }

    public function search()
    {
        $search = Request::get('q');
        if ($search == "") {
            return view('web.search')->with([
                'results' => collect([]),
                'search' => $search,
            ]);
        }
        $search_param = Request::get('search_param');

        if ($search_param == 'artist') {
            $results = DB::table('lyrics')
                ->where('artist', 'LIKE', "%$search%")
                ->get();
        } elseif ($search_param == 'title') {
            $results = DB::table('lyrics')
                ->where('title', 'LIKE', "%$search%")
                ->get();
        } elseif ($search_param == 'lyrics') {
            $results = Lyric::search($search)->get();
        } else {
            $results = Lyric::search($search)->get();
            /*//Convert string to array by splitting space char
            $words = preg_split('/[\s]+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            $wordsCount = count($words);
            $i = 0;
            $queries = collect([]);
            while ($queries->isEmpty() && $i < $wordsCount) {
                $queries = DB::table('lyrics')
                    ->where('artist', 'LIKE', "%$words[$i]%")
                    ->orWhere('title', 'LIKE', "%$words[$i]%")
                    ->get();
                $i++;
            }

            if (!$queries->isEmpty()) {
                for ($j = $i - 1; $j < $wordsCount; $j++) {
                    $results = collect([]);
                    foreach ($queries as $query) {
                        if (stripos($query->title,$words[$j]) !== false || stripos($query->artist,$words[$j]) !== false)
                        {
                            $results->push($query);
                        }
                    }
                    if (!$results->isEmpty()) {
                        $queries = $results;
                    }
                }
            } else {
                $results = $queries;
            }*/
        }

        return view('web.search')->with([
            'results' => $results,
            'search' => $search,
        ]);
    }

    public function autocomplete()
    {
        //$term which jquery ui generated contains words user type
        $term = Request::get('term');
        $queries = DB::table('lyrics')
                ->where('title', 'LIKE', "%$term%")
                ->orWhere('artist', 'LIKE', "%$term%")
                ->take(5)->get();
        foreach ($queries as $query) {
            $results[] = [
                'id' => $query->id,
                'value' => $query->title.' '.$query->artist,
            ];
        }

        return Response::json($results);
    }
}
