@extends('layouts.web.myplay')
@push('css')
<style>
    .videowrapper {
        float: none;
        clear: both;
        width: 100%;
        position: relative;
        padding-bottom: 46.25%;
        /*padding-top: 25px;*/
        /*height: 0;*/
    }
    .videowrapper iframe {
        position: absolute;
        /*top: 0;*/
        left: 0;
        width: 100%;
        height: 70%;
    }
    .lyric-player {
        font-size: large;
        text-align: center;
    }
    input.sentence {
        height: 35px;
        border: none;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
    }
    input.sentence:focus {
        outline-width: 0;
    }
    #first-sentence {
       margin-bottom: 20px;
    }
    .btn-skip, .btn-repeat, .btn-result {
        color: white;
        font-weight: bold;
        background-color: #5bc0de;
    }
    .btn-result {
        display: none;
    }
    .highlight-word {
        color: #5bc0de;
        font-weight: bold;
    }
    .wrong-input {
        color: red;
    }
    b#score {
        color: #5bc0de;
    }
</style>
@endpush
@section('content')
<div class="show-top-grids">
    <div class="col-sm-10 single-left">
        <div class="videowrapper song">
            <div class="song-info">
                <h3 class="song-meta">{{ $lyric->title }} - {{ $lyric->artist }}</h3>
                <form action="{{ action("Web\UserController@saveScore") }}" method="POST" class="lyric-meta-form">
                    {{ csrf_field() }}
                    <input type="hidden" class="song-thumbnail"
                        value="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg">
                    <input type="hidden" name="lyric_id" class="lyric-id" value="{{ $lyric->id }}">
                    <input type="hidden" name="level" class="level" value="{{ $level }}">
                </form>
            </div>
            <div class="video-grid" id="video-player" data-videoid="{{ $lyric->link_id }}"></div>
        </div>
        <div id="lyric-player" class="lyric-player">
            <div id="first-sentence"></div>
            <div id="second-sentence"></div>
        </div>
        <div>
            <button class="btn btn-repeat pull-left">Repeat</button>
            <button class='btn btn-skip pull-right'>Skip</button>
            <button type="button" class="btn btn-result pull-right" data-toggle="modal"
                data-target=".modal-result" data-url="{{ action("Web\UserController@saveScore") }}">Result
            </button>
        </div>
        <div class="modal modal-result fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Results</h4>
                    </div>
                    <div class="modal-body">
                        <h3>Your score: <b id="score"></b> points</h3>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-primary fb-share-result" data-url="{{ Request::fullUrl() }}">
                            Share on Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"> </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        var lyricContent = $('#lyric-player')[0];
        MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

        var observer = new MutationObserver(function(mutations, observer) {
            mutations.forEach(function(mutation) {
                //console.log(mutation.type);
                $('#lyric-player :input:enabled:visible:first').focus();
            });
        });

        observer.observe(lyricContent, {
          childList: true,
          subtree: true,
          characterData: true
        });
    });
    var sentences = {!! json_encode($sentences) !!}
    var currentSentence, checked, i = paused = replay = score = 0;
    var sentenceNumber = sentences.length;
    var timers = [];
    var complete = [];
    for (var k = 0; k < sentenceNumber; k++) {
        complete[k] = 0;
    }
    var level = $('.lyric-meta-form .level').val();
    if (level == 0) {
        $('.btn-repeat, .btn-skip').css('display', 'none');
    }

    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('video-player', {
            videoId: $('#video-player').data('videoid'),
            playerVars: {
                'autoplay' : 0,
                'controls' : 2,
                'autohide' : 1,
                'rel': 0,
                'showinfo' : 0,
                'fs': 0,
                'disablekb' : 0,
                'iv_load_policy': 3,
                'start' : 9,
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        //event.target.playVideo();
    }

    function Timer(callback, delay) {
        var id, started, remaining = delay, running;

        this.resume = function() {
            running = true;
            started = new Date();
            window.clearTimeout(id);
            id = setTimeout(callback, remaining);
        }

        this.pause = function() {
            running = false;
            window.clearTimeout(id);
            remaining -= new Date() - started;
        }

        this.getRemaining = function() {
            if (running) {
                this.pause();
                this.resume();
            }

            return remaining;
        }

        this.getStateRunning = function() {
            return running;
        }

        this.resume();
    }

    function isExist(x) {
        return (typeof x != "undefined") ? true : false;
    }

    function hasPositiveRemaining(timer) {
        return (isExist(timer) && timer.getRemaining() > 0) ? true : false;
    }

    function hasTimerToPause() {
        var i;
        for (i = 0; i < 3; i++) {
            if (hasPositiveRemaining(timers[i])) {
                return i;
            }
        }

        return -1;
    }

    function setTimeoutToPause(timerIndex, timeout, sentenceIndex) {
        var i = timerIndex, j;

        for (j = 0; j < 3; j++) {
            if (isExist(timers[j])) {
                timers[j].pause();
            }
        }

        if (isExist(timers[i])) {
            timers[i].pause();
        }

        timers[i] = new Timer(function () {
            pauseVideo(sentenceIndex);
        }, timeout);
    }

    function setTimeoutToAnimate(timerIndex, timeout, sentenceIndex) {
        var i = timerIndex;

        if (isExist(timers[i])) {
            timers[i].pause();
        }

        timers[i] = new Timer(function () {
            animateWord(sentenceIndex);
        }, timeout);
    }

    function pauseTimers() {
        var i;
        for (i = 0; i < 7; i++) {
            if (isExist(timers[i])) {
                timers[i].pause();
            }
        }
    }

    function resumeTimer() {
        var i;
        for (i = 3; i < 7; i++) {
            if (hasPositiveRemaining(timers[i])) {
                timers[i].resume();
            }
        }
    }

    var cc = 0;
    function animateWord(sentenceIndex) {
        var sentence = sentences[sentenceIndex];
        var wordTotal = sentence.lackWords.length;
        var duration = sentence.lackWords[cc]['duration'];

        $('#w_'+ sentenceIndex + cc).addClass('highlight-word');
        cc++;
        if (cc >= wordTotal) {
            cc = 0;
            return;
        }

        setTimeoutToAnimate(5, duration, sentenceIndex);
    }

    function replaySentence(i) {
        replay = 1;
        paused = 0;
        pauseTimers();
        player.seekTo(sentences[i].start / 1000, true);
        player.playVideo();

        cc = 0;
        animateWord(i);
        setTimeoutToPause(4, sentences[i].end - sentences[i].start, i);

        if (++i < sentenceNumber) {
            setTimeoutToAnimate(6, sentences[i].start - sentences[i - 1].start, i);
            setTimeoutToPause(3, sentences[i].end - sentences[i - 1].start, i);
        }
    }

    function joinWords(sentenceIndex) {
        return sentences[sentenceIndex].lackWords.map(function(word) {
            return word.word;
        }).join(" ");
    }

    function isCorrect(input, i) {
        return (input.val().toUpperCase() != sentences[i].fullWords[input.data('wordid')]['word'].toUpperCase())
            ? false
            : true;
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            if (paused == 1) {
                player.pauseVideo();
            } else {
                resumeTimer();
                var timerIndex = hasTimerToPause();
                if (timerIndex >= 0) {
                    timers[timerIndex].resume();
                }
            }
            setInterval(function () {
                //console.log('t');
                var currentTime = 1000 * player.getCurrentTime();
                    if (i < sentenceNumber && sentences[i].start <= currentTime && currentTime <= sentences[i].end
                            && (complete[i - 1] == 1 || i == 0) && replay == 0) {
                        if (hasTimerToPause() < 0) {
                            var timeout = (i == 0)
                                ? (sentences[i].end - sentences[i].start)
                                : (sentences[i].end - sentences[i - 1].end);

                            setTimeoutToPause(0, timeout, i);
                        }
                        $('#first-sentence').html(joinWords(i));
                        cc = 0;
                        animateWord(i);

                        if (++i < sentenceNumber) {
                            $('#second-sentence').html(joinWords(i));
                            setTimeoutToAnimate(6, sentences[i].start - sentences[i - 1].start, i);
                            i++;
                        }
                    }
            }, 1);
        }

        if (event.data == YT.PlayerState.PAUSED) {
            pauseTimers();
            if (paused == 1) {
                score--;
            }
        }

        if (event.data == YT.PlayerState.ENDED && i++ == sentenceNumber) {
            var lyricForm = $('.lyric-meta-form');
            var data = lyricForm.serializeArray();
            if (level > 0) {
                score = (score > 0) ? score : 0;
                $('.btn-repeat, .btn-skip').css('display', 'none');
                $('.btn-result').css('display', 'block').click();
                displayResult();
                data.push({name: 'score', value: score});
            }
            $.post(lyricForm.attr('action'), data);
        }
    }

    function pauseVideo(i) {
        if ($('.s_' + i).length) {
            //console.log('1');
            var inputNumber = rightInputNumber = 0;
            $('.s_' + i).each(function () {
                var $this = $( this );
                inputNumber++;
                console.log(sentences[i].fullWords[$this.data('wordid')]['word']);
                if (!isCorrect($this, i)) {
                    checked = 1;
                    currentSentence = i;
                    var timeoutToPause;
                    var timeoutToMute;
                    if (i + 1 < sentenceNumber && 1000 * (player.getCurrentTime() + 1) < sentences[i + 1].end) {
                        timeoutToPause = 1000;
                        timeoutToMute = 100;
                    } else {
                        timeoutToPause = 100;
                        timeoutToMute = 10;
                    }
                    var volume = 100;
                    var decreaseVolume = setInterval(function () {
                        volume -= 10;
                        player.setVolume(volume);
                    }, timeoutToMute);

                    if (isExist(timers[7])) {
                        timers[7].pause();
                    }
                    timers[7] = new Timer(function () {
                        player.pauseVideo();
                        paused = replay = 1;
                    }, timeoutToPause);

                    timers[8] = new Timer(function () {
                        clearInterval(decreaseVolume);
                        player.setVolume(100);
                    }, timeoutToPause);

                    return false;
                } else {
                    rightInputNumber++;
                }
            });
            if (rightInputNumber == inputNumber) {
                paused = replay = 0;
                complete[i] = 1;
                if (++i < sentenceNumber) {
                    setTimeoutToPause(1, sentences[i].end - sentences[i - 1].end, i);
                }
            }
        } else {
            console.log(player.getCurrentTime());
            paused = replay = 0;
            complete[i] = 1;
            if (++i < sentenceNumber) {
                setTimeoutToPause(2, sentences[i].end - sentences[i - 1].end, i);
            }
        }
    }

    function displayResult() {
        $('.modal-result #score').html(score);
    }

    $('#lyric-player').on("keyup", '.sentence', function() {
        var $this = $( this );
        var i = $this.data('sentenceid');
        var wordId = $this.data('wordid');
        var input = $this.data('input');
        var inputNumber = rightInputNumber = 0;
        var inputLength = $this.val().length;
        var maxLength = $this.attr('maxlength');

        if (inputLength == maxLength && !isCorrect($this, i)) {
            $this.addClass('wrong-input');
        } else {
            $this.removeClass('wrong-input');
        }

        $('.s_' + i).each(function () {
            var $this = $( this );
            inputNumber++;
            if (isCorrect($this, i)) {
                rightInputNumber++;
                if (!$this.prop('disabled')) {
                    score += $this.val().length;
                    $this.parent().nextAll('span').find('input').first().focus();
                    $this.prop('disabled', true);
                }
            }
        });

        if (isCorrect($this, i) && input == inputNumber) {
            $('#lyric-player :input:enabled:visible:first').focus();
        }

        if (inputNumber == rightInputNumber && (complete[i - 1] == 1 || i == 0) && checked == 1) {
            complete[i] = 1;
            checked = 0;
            if (isExist(timers[7])) {
                timers[7].pause();
            }
            if (player.getPlayerState() == 2 && paused == 1) {
                replaySentence(i);
            } else  {
                if (++i < sentenceNumber) {
                    setTimeoutToPause(1, sentences[i].end - sentences[i - 1].end, i);
                }
            }
        }
    });

    $('.btn-skip').click(function () {
        complete[i] = 1;
        if (player.getPlayerState() == 2 && paused == 1) {
            var i = currentSentence;
            score--;
            $('.s_' + i).each(function () {
                var $this = $( this );
                $this.val(sentences[i].fullWords[$this.data('wordid')]['word']);
                if (!$this.prop('disabled')) {
                    $this.removeClass('wrong-input');
                    $this.prop('disabled', true);
                }
            });
            replaySentence(i);
            $('#lyric-player :input:enabled:visible:first').focus();
        }
    });

    $('.btn-repeat').click(function () {
        if (player.getPlayerState() == 2 && paused == 1) {
            score--;
            replaySentence(currentSentence);
            $('#lyric-player :input:enabled:visible:first').focus();
        }
    });

    $('.btn-result').click(function () {
        displayResult();
    });

    $('#lyric-player').on('keydown', 'input', function(e) {
        if (e.keyCode == 32) return false;
    });

    $('.fb-share-result').click(function() {
        FB.ui({
            method: 'share',
            display: 'popup',
            href: $(this).data('url'),
            picture: $('.song-thumbnail').val(),
            title: 'You won ' + score + ' points',
            description: 'Learning English with the lyrics of ' + $('.song-meta').html(),
        }, function(response){});
    });
</script>
@endpush
