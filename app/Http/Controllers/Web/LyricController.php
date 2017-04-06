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
        $lyric = Lyric::findorFail($id);

        $initialLyric = $lyric->lyric;
        //$s = (string) $initialLyric;

        // Remove [..], (..), and symbol chars
        // $s = preg_replace('~\([^()]*\)+~', '', $s);
        // $s = preg_replace('~\[[^\]]*\]+~', '', $s);
        // $s = preg_replace('/[^\p{L}\p{N}\s\']/u', '', $s);

        //Convert string to array by splitting space char
        //$s = preg_split('/[\s]+/', $s, -1, PREG_SPLIT_NO_EMPTY);

        $sentences = convertSRTFormatToArray($initialLyric);
        $sentenceIndexes = range(0, count($sentences) - 1);

        if ($level == 'easy') {
            $rate = 0.1;
        } elseif ($level == 'medium') {
            $rate = 0.25;
        } else {
            $rate = 0.5;
        }

        $wordTotal = 0;
        foreach ($sentences as $sentence) {
            $wordTotal += count($sentence->fullWords);
        }

        $hiddenWordTotal = round($rate*$wordTotal);
        $hiddenWordCount = 0;
        while ($hiddenWordCount < $hiddenWordTotal) {
            shuffle($sentenceIndexes);
            $sentenceIndex = $sentenceIndexes[0];
            $sentence = $sentences[ $sentenceIndex ];
            $words = $sentence->fullWords;
            $remainHiddenWordNo = $hiddenWordTotal - $hiddenWordCount;
            $averageHiddenWordNo = ceil($rate*count($words));
            $hiddenWordNumber = ($remainHiddenWordNo < $averageHiddenWordNo)
                ? $remainHiddenWordNo
                : $averageHiddenWordNo;
            $hiddenWordIndexes = array_rand($words, $hiddenWordNumber);
            if (!is_array($hiddenWordIndexes)) {
                $hiddenWordIndexes = (array) $hiddenWordIndexes;
            }

            foreach ($hiddenWordIndexes as $i) {
                $sentence->lackWords[$i] = '<input class="sentence s_' . $sentenceIndex. '" data-wordid="' . $i .'" data-sentenceid="' .$sentenceIndex. '">';
            }

            array_splice($sentenceIndexes, 0, 1);
            $hiddenWordCount += $hiddenWordNumber;
        }

        //dd($sentences);

        //Arrange positions incrementally
        // for ($i = 0; $i < $num_words; $i++) {
        //     for ($j = $num_words - 1; $j > $i; $j--) {
        //         if ($position[$j] < $position[$j - 1]) {
        //             $tmp = $position[$j];
        //             $position[$j] = $position[$j - 1];
        //             $position[$j - 1] = $tmp;
        //         }
        //     }
        // }

        //Replace hidden words with input tag
        // for ($i = 0; $i < $num_words; $i++) {
        //     $p = $position[$i];
        //     $answers[$i] = $s[$p];
        //     $num_chars = strlen($answers[$i]);
        //     //$s[$p] = '<input id="lrc_cell'.$i.'" class="cell" type="text" size="'.$num_chars.'" name="usr_ans'.$i.'">';
        // }
        // $hidden_lyric = implode(' ', $s);

        // preg_match_all("/\[([^\]]*)\]/", $initialLyric, $timestamps);

        // $sentences = preg_split('/\[([^]]+)\]/', $initialLyric, -1, PREG_SPLIT_NO_EMPTY);
        // $sentences = array_map('rtrim', $sentences);
        // $split = function(&$value) {
        //     return preg_split('/[\s]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        // };
        // $sentences = array_map($split, $sentences);

        return view('web.lyric.show')->with([
            'lyric' => $lyric,
            'sentences' => $sentences,
        ]);
    }

    public function check(Request $req)
    {
        $num_words = $req->input('num_words');
        $lyricId = $req->input('lyric_id');

        DB::beginTransaction();
        try {
            if (Auth::check()) {
                Auth::user()->lyrics()->attach($lyricId, [
                    'score' => $req->input('score'),
                    'total_word' => $num_words
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
