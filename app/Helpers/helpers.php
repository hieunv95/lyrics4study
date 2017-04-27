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
    if ($level == 1) {
        return "Easy";
    } elseif ($level == 2) {
        return "Medium";
    } elseif ($level == 3) {
        return "Hard";
    } else {
        return "Expert";
    }
}
