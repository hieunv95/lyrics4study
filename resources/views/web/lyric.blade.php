@extends('layouts.web.myplay')

@push('meta')
<meta property="og:url" content="{{ Request::fullUrl() }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ config('app.name') }}" />
<meta property="og:description" content="Lyrics4Study - Learning English through lyrics" />
<meta property="og:image" content="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg" />
@endpush

@section('content')
<style>
pre {
    word-break: keep-all;
    white-space: normal;
    line-height: 3;
    font-size: 15px;
}

input.cell {
    width: 70px;
    height: 35px;
    border: 1px solid #ccc;
    border-radius: 4px;
    text-align: center;
}
.results ul {
    list-style-type: none;
    font-size: 15px;
}
.answer {
    font-weight: bold;
}
.right-answer {
    color: blue;
}
.correct-answer {
    color: green;
}
.uncorrect-answer {
    color: red;
    text-decoration: line-through;
}
.unfilled-answer {
    color:blue;
    text-decoration: underline;
}
</style>
<div class="show-top-grids">
    <div class="col-sm-8 single-left">
        <div class="song">
            <div class="song-info">
                <h3 class="song-meta">{{ $lyric->title }} - {{ $lyric->artist }}</h3>
                <input type="hidden" class="song-thumbnail"
                    value="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg">
            </div>
            <div class="video-grid">
                {{-- <iframe src="https://www.youtube.com/embed/{{ $lyric->link_id }}?autohide=1&autoplay=0&cc_load_policy=1&controls=1&disablekb=1&enablejsapi=1&fs=0&iv_load_policy=3&loop=0&modestbranding=0&origin=http%3A%2F%2Flyrics4study&rel=0&showinfo=0&theme=dark&playsinline=1" frameborder="0" allowfullscreen="0"></iframe> --}}
            </div>
        </div>
        <div class="song-grid-right">
            <div class="share">
            <h5>Share this</h5>
                <ul>
                    <li>
                        <a href="#" class="icon fb-icon fb-share-lyric" data-url="{{ Request::fullUrl() }}">
                            Facebook
                        </a>
                    </li>
                    <li><a href="#" class="icon twitter-icon">Twitter</a></li>
                    <li><a href="#" class="icon like">Like</a></li>
                    <li class="view">200 Views</li>
                </ul>
            </div>
        </div>
        <div class="clearfix"> </div>
        <div class="published">
            <div class="load_more">
                <form action="{{ action('Web\LyricController@check') }}" method="POST"
                    class="form-horizontal lyrics-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="num_words" value="{{ $num_words }}">
                    <input type="hidden" name="lyric_id" value="{{ $lyric->id }}">
                </form>
                <h4>Lyric:</h4>
                <pre>{!! $hid_lrc!!}</pre>
                @for ($i = 0; $i < $num_words; $i++)
                    <input type="hidden" id="answer_{{ $i }}" name="ans{{ $i }}" value="{{ $answers[$i] }}">
                @endfor
                <div class="btn-group-check">
                        <button type="button" id="check" class="btn btn-info">Check</button>
                        <button type="button" id="view_answers" class="btn btn-info" data-toggle="modal"
                            data-target="#modal_result">View Answers
                        </button>
                </div>
                <div class="modal fade" id="modal_result" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Results</h4>
                            </div>
                            <div class="modal-body">
                                <h3 id="score"></h3>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-primary fb-share-result" data-url="{{ Request::fullUrl() }}">
                                    Share on Facebook
                                </a>
                                {{-- <button type="button" class="btn btn-info" data-dismiss="modal">Close</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="results" style="display: none">
                    <ul>
                        <li>Results:</li>
                        <li style="color:green;">
                            Correct words amount: <span id="correct_words"></span>
                        </li>
                        <li style="color:red;">
                            Uncorrect words amount: <span id="uncorrect_words"></span>
                        </li>
                        <li style="color:blue;">
                            Unfilled words amount: <span id="unfilled_words"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"> </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        window.fbAsyncInit = function() {
            FB.init({
              appId      : 1226236657472789,
              xfbml      : true,
              version    : 'v2.8'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        $("button#check").click(function () {
            checkAnswers();
        });
        $("button#view_answers").click(function () {
            viewAnswers();
        });
    });
    function checkAnswers() {
        var wordsTotal = $('input[name="num_words"]').val();
        for (var i = 0; i < wordsTotal; i++) {
            var usr_ans = $('input#lrc_cell' + i).val();
            var ans = $('input#answer_' + i).val();
            if (usr_ans.toLowerCase() == ans.toLowerCase()) {
                $("input#lrc_cell" + i).css("background-color", "#88B04B");
            }
            else {
                $("input#lrc_cell" + i).css("background-color", "#F7786B");
                $("input#lrc_cell" + i).effect( "shake", {times:1, distance:10, direction:'up'}, 1000);
            }
        }
    }
    function viewAnswers() {
        var correctWordAmount = uncorrectWordAmount = 0;
        var unfilledWordAmount;
        var lyricsForm = $('.lyrics-form');
        var data = lyricsForm.serializeArray();
        var wordsTotal = $('input[name="num_words"]').val();
        for (var i = 0; i < wordsTotal; i++) {
            var usr_ans = $('input#lrc_cell' + i).val();
            var ans = $('input#answer_' + i).val();
            if (usr_ans != '') {
                if (usr_ans.toLowerCase() == ans.toLowerCase()) {
                    var rightAnswer = '<span class="answer right-answer">' + ans + '</span>';
                    var userAnswer = '<span class="answer correct-answer">' + usr_ans + '</span>';
                    $("input#lrc_cell" + i).replaceWith(rightAnswer + '/' + userAnswer);
                    correctWordAmount++;
                }
                else {
                    var rightAnswer = '<span class="answer right-answer">' + ans + '</span>';
                    var userAnswer = '<span class="answer uncorrect-answer">' + usr_ans + '</span>';
                    $("input#lrc_cell" + i).replaceWith(rightAnswer + '/' + userAnswer);
                    uncorrectWordAmount++;
                }
            }
            $("input#lrc_cell" + i).replaceWith('<span class="answer unfilled-answer">' + ans + '</span>');
        }
        $('h3#score').html('Your score: ' + correctWordAmount + '/' + {{ $num_words }});
        $('#correct_words').html(correctWordAmount);
        $('#uncorrect_words').html(uncorrectWordAmount);
        unfilledWordAmount = {{ $num_words }} - correctWordAmount - uncorrectWordAmount;
        $('#unfilled_words').html(unfilledWordAmount);
        $('.results').show();
        $('.btn-group-check').hide();
        data.push({name: 'score', value: correctWordAmount});
        $.post(lyricsForm.attr('action'), data);
        $('.fb-share-result').click(function() {
            FB.ui({
                method: 'share',
                display: 'popup',
                href: $(this).data('url'),
                picture: $('.song-thumbnail').val(),
                title: 'You filled ' + correctWordAmount + '/' + wordsTotal + ' words correctly !',
                description: 'Learning English with the lyrics of ' + $('.song-meta').html(),
            }, function(response){});
        });
    }

    $('.fb-share-lyric').click(function() {
        FB.ui({
            method: 'share',
            display: 'popup',
            href: $(this).data('url'),
            picture: $('.song-thumbnail').val(),
            title: $('.song-meta').html(),
            description: 'Lyrics4Study - Learning English with lyrics',
        }, function(response){});
    });
</script>
@endsection
