<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lyric;
use Auth;
use DB;

class LyricController extends Controller
{
    public function show($artist, $title, $id)
    {
        return view('web.level', compact('artist', 'title', 'id'));
    }

    public function play($artist, $title, $id, $level)
    {
        $lyric = Lyric::find($id);

        $s = $lyric->lyric;
        $s = (string) $s;

        // Remove [..], (..), and symbol chars
        $s = preg_replace('~\([^()]*\)+~', '', $s);
        $s = preg_replace('~\[[^\]]*\]+~', '', $s);
        $s = preg_replace('/[^\p{L}\p{N}\s\']/u', '', $s);

        //Convert string to array by splitting space char
        $s = preg_split('/[\s]+/', $s, -1, PREG_SPLIT_NO_EMPTY);

        $array = range(0, count($s) - 1);

        if ($level == 'easy') {
            $lvl = 0.2;
        } elseif ($level == 'medium') {
            $lvl = 0.3;
        } else {
            $lvl = 0.5;
        }
        //Number of hidden words
        $num_words = round($lvl*count($s));

        //Random positions of hidden words
        for ($i = 0; $i < $num_words; $i++) {
            shuffle($array);
            $position[$i] = $array[0];
            array_splice($array, 0, 1);
        }

        //Arrange positions incrementally
        for ($i = 0; $i < $num_words; $i++) {
            for ($j = $num_words - 1; $j > $i; $j--) {
                if ($position[$j] < $position[$j - 1]) {
                    $tmp = $position[$j];
                    $position[$j] = $position[$j - 1];
                    $position[$j - 1] = $tmp;
                }
            }
        }

        //Replace hidden words with input tag
        for ($i = 0; $i < $num_words; $i++) {
            $p = $position[$i];
            $answers[$i] = $s[$p];
            $num_chars = strlen($answers[$i]);
            $s[$p] = '<input id="lrc_cell'.$i.'" class="cell" type="text" size="'.$num_chars.'" name="usr_ans'.$i.'">';
        }
        $hidden_lyric = implode(' ', $s);

        return view('web.lyric')->with([
            'lyric' => $lyric,
            'hid_lrc' => $hidden_lyric,
            'num_words' => $num_words,
            'answers' => $answers,
        ]);
    }

    public function check(Request $req)
    {
        $num_words = $req->input('num_words');
        $lyricId = $req->lyric_id;
        $score = 0;
        for ($i = 0; $i < $num_words; $i++) {
            $user_answers[$i] = $req->input('usr_ans' .$i);
            $answers[$i] = $req->input('ans' .$i);
            if ($user_answers[$i] == $answers[$i]) {
                $score++;
            }
        }

        DB::beginTransaction();
        try {
            if (Auth::check()) {
                Auth::user()->lyrics()->attach($lyricId, ['score' => $score, 'total_word' => $num_words]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return view('web.answer')->with([
            'usr_ans' => $user_answers,
            'ans' => $answers,
            'num_words' => $num_words,
        ]);
    }
}
