@extends('layouts.web.myplay')

@section('content')
<style>
pre {
    word-break: keep-all;
    white-space: normal;
    line-height: 3;
    font-size: 15px
}

input.cell {
    width: 70px;
    height: 35px;
    /* box-sizing: border-box; */
    border: 1px solid #ccc;
    border-radius: 4px;
    /* font-size: 15px; */
    text-align: center;
    /* background-color: white;
    background-image: url('searchicon.png');
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 12px 20px 12px 40px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out; */
}
</style>
    <div class="show-top-grids">
        <div class="col-sm-8 single-left">
            <div class="song">
                <div class="song-info">
                    <h3>{{ $lyric->title }} - {{ $lyric->artist }}</h3>
                </div>
                <div class="video-grid">
                    <iframe src="https://www.youtube.com/embed/{{ $lyric->link_id }}?autohide=1&autoplay=0&cc_load_policy=1&controls=1&disablekb=1&enablejsapi=1&fs=0&iv_load_policy=3&loop=0&modestbranding=0&origin=http%3A%2F%2Flyrics4study&rel=0&showinfo=0&theme=dark&playsinline=1" frameborder="0" allowfullscreen="0"></iframe>
                </div>
            </div>
            <div class="song-grid-right">
                <div class="share">
                    <h5>Share this</h5>
                        <ul>
                            <li><a href="#" class="icon fb-icon">Facebook</a></li>
                            <li><a href="#" class="icon twitter-icon">Twitter</a></li>
                            <li><a href="#" class="icon like">Like</a></li>
                            <li class="view">200 Views</li>
                        </ul>
                </div>
            </div>
            <div class="clearfix"> </div>
            <div class="published">
                <div class="load_more">
                    <form action="{{ action('Web\LyricController@check') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                            <h4>Lyric:</h4>
                            <pre>{!! $hid_lrc!!}</pre>
                            @for ($i = 0; $i < $num_words; $i++)
                                <input type="hidden" name="ans{{ $i }}" value="{{ $answers[$i] }}">
                            @endfor
                            <input type="hidden" name="num_words" value="{{ $num_words }}">
                            <input type="hidden" name="lyric_id" value="{{ $lyric->id }}">
    <button type="button" id="chk" class="btn btn-info" data-toggle="modal" data-target="#check">Check</button>
    <div class="modal fade" id="check" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Results</h4>
                </div>
                <div class="modal-body">
                    <h3 id="scr"></h3>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">View answers</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
                </form>
            </div>
        </div>
    </div>
            <div class="clearfix"> </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $("button#chk").click(function() {
        checkAnswers();
        });
    });
function checkAnswers() {
    var score = 0;
    @for ($i = 0; $i < $num_words; $i++)
        var usr_ans = $('input[name="usr_ans{{ $i }}"]').val();
        var ans = "{!! $answers[$i] !!}";
            if (usr_ans.toLowerCase() == ans.toLowerCase()) {
                //$("div#cell{{ $i }}").attr('class','has-success has-feedback');
                $("input#lrc_cell{{ $i }}").css("background-color", "#88B04B");
                score++;
            }
            else {
                //$("div#cell{{ $i }}").attr('class','has-error has-feedback');
                $("input#lrc_cell{{ $i }}").css("background-color", "#F7786B");
            }
    @endfor
    $('h3#scr').html('Your score: ' + score + '/' + {{ $num_words }});
}
</script>
@endsection
