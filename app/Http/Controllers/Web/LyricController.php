<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lyric;
use Auth;
use DB;
use File;
use App\Http\Requests\StoreLyric;
use App\Http\Requests\UpdateLyric;

class LyricController extends Controller
{
    public function show($artist, $title, $id)
    {
        return view('web.level', compact('artist', 'title', 'id'));
    }

    public function create()
    {
        return view('web.lyric.create');
    }

    public function store(StoreLyric $request)
    {
        $input = $request->only('title', 'artist', 'yt_link', 'lyric_file');
        $lyric = new Lyric;
        $lyric->title = $input['title'];
        $lyric->artist = $input['artist'];
        $lyric->link_id = parse_yturl($input['yt_link']);
        $lyric->lyric = $request->file('lyric_file');
        $lyric->user_id = Auth::id();
        $lyric->save();

        return redirect()
            ->action('Web\UserController@showLyrics' , Auth::id())
            ->with('status', 'Lyric created!');
    }

    public function edit($lyricId) {
        $lyric = Lyric::findorFail($lyricId);
        $this->authorize('update', $lyric);
        $ytLink = 'https://www.youtube.com/watch?v=' . $lyric->link_id;

        return view('web.lyric.edit')->with([
            'lyric' => $lyric,
            'ytLink' => $ytLink,
        ]);
    }

    public function update($lyricId, UpdateLyric $request) {
        $lyric = Lyric::findorFail($lyricId);
        $input = $request->only('title', 'artist', 'yt_link', 'lyric_file');
        if ($request->hasfile('lyric_file')) {
            $lyric->lyric = $request->file('lyric_file');
        }

        $lyric->title = $input['title'];
        $lyric->artist = $input['artist'];
        $lyric->link_id = parse_yturl($input['yt_link']);
        $lyric->save();

        return redirect()
            ->action('Web\UserController@showLyrics' , Auth::id())
            ->with('status', 'Lyric updated!');
    }

    public function delete($lyricId) {
        $lyric = Lyric::findorFail($lyricId);
        $this->authorize('delete', $lyric);

        DB::beginTransaction();
        try {
            $lyric->users()->detach();
            $lyric->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()
            ->action('Web\UserController@showLyrics' , Auth::id())
            ->with('status', 'Lyric deleted!');
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

        switch($level) {
            case 'karaoke':
                $rate = 0;
                $level = 0;
                break;
            case 'easy':
                $rate = 0.1;
                $level = 1;
                break;
            case 'medium':
                $rate = 0.25;
                $level = 2;
                break;
            case 'hard':
                $rate = 0.5;
                $level = 3;
                break;
            case 'expert':
                $rate = 1;
                $level = 4;
                break;
            default:
                abort(404);
                break;
        }

        $wordTotal = 0;
        foreach ($sentences as $sentenceIndex => $sentence) {
            $wordTotal += count($sentence->fullWords);
            foreach ($sentence->lackWords as $wordId => $word) {
                $sentence->lackWords[$wordId]['word'] = '<span id="w_' . $sentenceIndex . $wordId . '">'
                    .$word['word']. '</span>';
            }
        }

        $hiddenWordTotal = round($rate * $wordTotal);
        $hiddenWordCount = 0;
        while ($hiddenWordCount < $hiddenWordTotal) {
            shuffle($sentenceIndexes);
            $sentenceIndex = $sentenceIndexes[0];
            $sentence = $sentences[ $sentenceIndex ];
            $words = $sentence->fullWords;
            $remainHiddenWordNo = $hiddenWordTotal - $hiddenWordCount;
            $averageHiddenWordNo = ceil($rate * count($words));
            $hiddenWordNumber = ($remainHiddenWordNo < $averageHiddenWordNo)
                ? $remainHiddenWordNo
                : $averageHiddenWordNo;
            $hiddenWordIndexes = array_rand($words, $hiddenWordNumber);
            if (!is_array($hiddenWordIndexes)) {
                $hiddenWordIndexes = (array) $hiddenWordIndexes;
            }

            $input = 1;
            foreach ($hiddenWordIndexes as $i) {
                $charNumber = strlen($sentence->fullWords[$i]['word']);
                $dotPlaceholder = '';
                for ($j = 0; $j < $charNumber; $j++) {
                    $dotPlaceholder .= '&bull;';
                }
                $sentence->lackWords[$i]['word'] = '<span id="w_'. $sentenceIndex . $i . '">
                    <input class="sentence s_'.$sentenceIndex.'"
                    data-wordid="'.$i.'" data-sentenceid="'.$sentenceIndex.'" data-input="'.$input++.'"
                    size="'.$charNumber.'" maxlength="'.$charNumber.'" placeholder="'.$dotPlaceholder.'"></span>';
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
            'level' => $level,
        ]);
    }
}
