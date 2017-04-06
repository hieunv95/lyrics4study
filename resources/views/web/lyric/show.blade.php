@extends('layouts.web.myplay')
@push('css')
<style>
    .videowrapper {
        float: none;
        clear: both;
        width: 100%;
        position: relative;
        padding-bottom: 56.25%;
        /*padding-top: 25px;*/
        height: 0;
    }
    .videowrapper iframe {
        position: absolute;
        /*top: 0;*/
        left: 0;
        width: 100%;
        height: 70%;
    }
    .lyric-player {
        font-size: medium;
    }
</style>
@endpush
@section('content')
<div class="show-top-grids">
    <div class="col-sm-8 single-left">
        <div class="videowrapper">
            <div class="song-info">
                <h3 class="song-meta">{{ $lyric->title }} - {{ $lyric->artist }}</h3>
                <input type="hidden" class="song-thumbnail"
                    value="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg">
            </div>
            <div class="video-grid" id="video-player" data-videoid="{{ $lyric->link_id }}"></div>
        </div>
        <div class="clearfix"> </div>
        <div class="published">
            <div class="load_more">
                <div id="lyric-player" class="lyric-player">
                    <div id="first-sentence"></div>
                    <div id="second-sentence"></div>
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
    });
    var sentences = {!! json_encode($sentences) !!}

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
                'start' : 20,
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        event.target.playVideo();
    }

    var i = 0;
    var paused = 0;
    var sentenceNumber = sentences.length;
    var complete = new Array(sentenceNumber);
    for (var k = 0; k < sentenceNumber; k++) {
        complete[k] = 0;
    }

    function Timer(callback, delay) {
        var id, started, remaining = delay, running

        this.resume = function() {
            running = true
            started = new Date()
            window.clearTimeout(id)
            id = setTimeout(callback, remaining)
        }

        this.pause = function() {
            running = false
            window.clearTimeout(id)
            remaining -= new Date() - started
        }

        this.getRemaining = function() {
            if (running) {
                this.pause()
                this.resume()
            }

            return remaining
        }

        this.getStateRunning = function() {
            return running
        }

        this.resume()
    }
    var timer, timer1, timer2, timer3, interval;
    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            if (paused == 1) {
                player.pauseVideo();
            } else if (typeof timer != "undefined" && timer.getRemaining() > 0) {
                timer.resume();
                console.log('timer');
            } else if (typeof timer1 != "undefined" && timer1.getRemaining() > 0) {
                timer1.resume();
                console.log('timer1');
            } else if (typeof timer2 != "undefined" && timer2.getRemaining() > 0) {
                timer2.resume();
                console.log('timer2');
            } else if (typeof timer3 != "undefined" && timer3.getRemaining() > 0) {
                timer3.resume();
                console.log('timer3');
            } else {
                interval = setInterval(function () {
                    var currentTime = player.getCurrentTime();
                        if (i < sentenceNumber && sentences[i].start <= currentTime && currentTime <= sentences[i].end
                            && (complete[i-1] == 1 || i == 0)) {
                            console.log('0');
                            let index = i;
                            var timeout = (i == 0)
                                ? (sentences[i].end - sentences[i].start)
                                : (sentences[i].end - sentences[i - 1].end);
                            if (typeof timer != "undefined") {
                                timer.pause();
                            }

                            timer = new Timer(function () {
                                pauseVideo(index);
                            }, 1000*(timeout));
                            $('#first-sentence').html(sentences[i++].lackWords.join(" "));
                            if (i < sentenceNumber) {
                                $('#second-sentence').html(sentences[i++].lackWords.join(" "));
                            }
                        }
                }, 1);
            }
        }
        if (event.data == YT.PlayerState.PAUSED) {
            if (typeof timer != "undefined") {
                console.log('ptimer');
                timer.pause();
            }
            if (typeof timer1 != "undefined") {
                console.log('ptimer1');
                timer1.pause();
            }
            if (typeof timer2 != "undefined") {
                console.log('ptimer2');
                timer2.pause();
            }
            if (typeof timer3 != "undefined") {
                console.log('ptimer3');
                timer3.pause();
            }
        }
    }

    function pauseVideo(index) {
        var i = index;
        if ($('.s_' + i).length) {
            console.log('1');
            let inputNumber = rightInputNumber = 0;
            $('.s_' + i).each(function () {
                inputNumber++;
                console.log(sentences[i].fullWords[$( this ).data('wordid')]);
                if ($( this ).val() != sentences[i].fullWords[$( this ).data('wordid')]) {
                    paused = 1;
                    player.pauseVideo();
                } else {
                    rightInputNumber++;
                }
            });
            if (rightInputNumber == inputNumber) {
                paused = 0;
                complete[i] = 1;
                i++;
                if (i < sentenceNumber) {
                    if (typeof timer1 != "undefined") {
                        timer1.pause();
                    }
                    timer1 = new Timer(function () {
                        pauseVideo(i);
                    }, 1000*(sentences[i].end - sentences[i-1].end));
                }
            }
        } else {
            console.log('2');
            paused = 0;
            complete[i] = 1;
            i++;
            if (i < sentenceNumber) {
                if (typeof timer2 != "undefined") {
                    timer2.pause();
                }
                timer2 = new Timer(function () {
                    pauseVideo(i);
                }, 1000*(sentences[i].end - sentences[i-1].end));
            }
        }
    }

    $('#lyric-player').on("keyup",'.sentence', function(){
        let i = $(this).data('sentenceid');
        let inputNumber = rightInputNumber = 0;
        $('.s_' + i).each(function () {
            inputNumber++;
            if ($( this ).val() == sentences[i].fullWords[$( this ).data('wordid')]) {
                rightInputNumber++;
            }
        });
        if (player.getPlayerState() == 2 && inputNumber == rightInputNumber && (complete[i-1] == 1 || i == 0)) {
            player.playVideo();
            // player.seekTo(sentences[i].start, true);
            paused = 0;
            complete[i] = 1;
            i++;
            if (i < sentenceNumber) {
                if (typeof timer3 != "undefined") {
                    timer3.pause();
                }
                timer3 = new Timer(function () {
                    console.log('3');
                    pauseVideo(i);
                }, 1000*(sentences[i].end - sentences[i-1].end));
            }
        }
    });
</script>
@endpush
