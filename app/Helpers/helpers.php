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
                    $sub->start = toSeconds($startTime);
                    $sub->end = toSeconds($endTime);
                    $subWords = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $subWords);
                    $subWords = preg_replace('/[^\p{L}\p{N}\s\']/u', '', $subWords);
                    $sub->fullWords = preg_split('/[\s]+/', $subWords, -1, PREG_SPLIT_NO_EMPTY);
                    $sub->lackWords = $sub->fullWords;
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

function toSeconds(string $time): float
    {
        list($hour, $minute, $second) = explode(':', $time);
        return (int)$hour * 3600 + (int)$minute * 60 + floatval(str_replace(',', '.', $second));
    }
