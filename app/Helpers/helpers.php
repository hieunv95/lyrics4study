<?php

function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null)
{
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
    $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
    parse_str(request()->getQueryString(), $query);
    unset($query[$pageName]);
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentPageItems,
        $collection->count(),
        $perPage,
        $currentPage,
        [
            'pageName' => $pageName,
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => $query,
            'fragment' => $fragment
        ]
    );

    return $paginator;
}

function convertSRTFormatToArray($lines) {

    define('SRT_STATE_SUBNUMBER', 0);
    define('SRT_STATE_TIME',      1);
    define('SRT_STATE_TEXT',      2);
    define('SRT_STATE_BLANK',     3);

    $subs = array();
    $state = SRT_STATE_SUBNUMBER;
    $subWords = '';
    $subTime = '';

    foreach(preg_split("/((\r?\n)|(\r\n?))/", $lines) as $line) {
        switch($state) {
            case SRT_STATE_SUBNUMBER:
                $state = SRT_STATE_TIME;
                break;

            case SRT_STATE_TIME:
                $subTime = trim($line);
                $state = SRT_STATE_TEXT;
                break;

            case SRT_STATE_TEXT:
                if (trim($line) == '') {
                    $sub = new stdClass;
                    list($startTime, $endTime) = array_pad(explode(' --> ', $subTime, 2), 2, null);
                    $sub->start = toMilliseconds($startTime);
                    $sub->end = toMilliseconds($endTime);
                    $subWords = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $subWords);
                    $subWords = preg_replace('/[^\p{L}\p{N}\s\']/u', '', $subWords);
                    $charNumber = strlen(preg_replace('/\s+/', '', $subWords));
                    $subWords = preg_split('/[\s]+/', $subWords, -1, PREG_SPLIT_NO_EMPTY);
                    $fullWords = [];
                    $averageDuration = ($sub->end - $sub->start) / $charNumber;
                    foreach ($subWords as $key => $word) {
                        $fullWords[$key] = [
                            'word' => $word,
                            'duration' => $averageDuration * strlen($word),
                        ];
                    }

                    $sub->fullWords = $sub->lackWords = $fullWords;
                    $subWords = '';
                    $state = SRT_STATE_SUBNUMBER;

                    $subs[] = $sub;
                } else {
                    $subWords .= $line;
                }
                break;
        }
    }

    return $subs;
}

function toMilliseconds(string $time): float
{
    list($hour, $minute, $second) = explode(':', $time);
    list($second, $millisecond) = explode(',', $second);
    return ((int)$hour * 3600 + (int)$minute * 60 + (int)$second) * 1000 + $millisecond;
}

function levelToString($level)
{
    switch($level) {
        case 1:
            return "Easy";
        case 2:
            return "Medium";
        case 3:
            return "Hard";
        case 4:
            return "Expert";
    }
}

/**
 *  Check if input string is a valid YouTube URL
 *  and try to extract the YouTube Video ID from it.
 *  @param   $url   string   The string that shall be checked.
 *  @return  mixed           Returns YouTube Video ID, or (boolean) false.
 */
function parse_yturl($url)
{
    $pattern = '#^(?:https?://|//)?' # Optional URL scheme. Either http, or https, or protocol-relative.
             . '(?:www\.|m\.)?'      #  Optional www or m subdomain.
             . '(?:'                 #  Group host alternatives:
             .   'youtu\.be/'        #    Either youtu.be,
             .   '|youtube\.com/'    #    or youtube.com
             .     '(?:'             #    Group path alternatives:
             .       'embed/'        #      Either /embed/,
             .       '|v/'           #      or /v/,
             .       '|watch\?v='    #      or /watch?v=,
             .       '|watch\?.+&v=' #      or /watch?other_param&v=
             .     ')'               #    End path alternatives.
             . ')'                   #  End host alternatives.
             . '([\w-]{11})'         # 11 characters (Length of Youtube video ids).
             . '(?![\w-])#';         # Rejects if overlong id.
    preg_match($pattern, $url, $matches);
    $videoId = isset($matches[1]) ? $matches[1] : false;
    if ($videoId) {
        $isExist = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' .$videoId);

        if ($isExist) {
            return $videoId;
        }
    }

    return false;
}
