@extends('layouts.web.myplay')
@section('content')
<div class="main-grids">
    <div class="top-grids">
        <div class="recommended-info">
            <h3>Top songs</h3>
        </div>
        @foreach ($t_lyrics as $lyric)
        <div class="col-md-4 resent-grid recommended-grid slider-top-grids">
            <div class="resent-grid-img recommended-grid-img">
                <a href="{{ route('show', ['id' => $lyric->id,'title' => str_slug($lyric->title,'-'),
                    'artist' => str_slug($lyric->artist,'-')]) }}">
                    <img src="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg"
                    alt="{{ $lyric->title }}-{{ $lyric->artist }}" />
                </a>
                {{-- <div class="time">
                    <p>3:04</p>
                </div>
                <div class="clck">
                    <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                </div> --}}
            </div>
            <div class="resent-grid-info recommended-grid-info">
                <h3>
                    <ul>
                        <li><a href="{{ route('show', ['id' => $lyric->id,'title' => str_slug($lyric->title,'-'),
                                'artist' => str_slug($lyric->artist,'-')]) }}"
                                class="title title-info">{{ $lyric->title }}
                            </a>
                        </li>
                        <li><a href="#" class="artist artist-info">{{ $lyric->artist }}</a></li>
                    </ul>
                </h3>
                <ul>
                    <li>
                        <p class="author author-info"><a href="#" class="author">Admin</a></p>
                    </li>
                    <li class="right-list">
                        <p class="views views-info">200 views</p>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        <div class="clearfix"> </div>
    </div>
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
                <h3>New songs</h3>
            </div>
            <div id="top" class="callbacks_container">
                <ul class="rslides" id="slider3">
                    @php $start = 0; @endphp
                    @for($i = 0; $i< 2; $i++)
                    <li>
                        <div class="animated-grids">
                            @for($j = $start; $j < $start + 4; $j++)
                             <div class="col-md-3 resent-grid recommended-grid slider-first">
                                <div class="resent-grid-img recommended-grid-img">
                                    <a href="{{ route('show', ['id' => $n_lyrics[$j]->id,
                                        'title' => str_slug($n_lyrics[$j]->title,'-'),
                                        'artist' => str_slug($n_lyrics[$j]->artist,'-')]) }}">
                                        <img src="https://i3.ytimg.com/vi/{{ $n_lyrics[$j]->link_id }}/mqdefault.jpg"
                                        alt="{{ $n_lyrics[$j]->title }}-{{ $n_lyrics[$j]->artist }}" />
                                    <div class="time small-time slider-time">
                                        <p>7:34</p>
                                    </div>
                                    <div class="clck small-clck">
                                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="resent-grid-info recommended-grid-info">
                                    <h5>
                                        <ul>
                                            <li><a href="{{ route('show',
                                                    ['id' => $n_lyrics[$j]->id,
                                                    'title' => str_slug($n_lyrics[$j]->title,'-'),
                                                    'artist' => str_slug($n_lyrics[$j]->artist,'-')]) }}"
                                                    class="title">{{ $n_lyrics[$j]->title }}
                                                </a>
                                            </li>
                                            <li><a href="#" class="artist artist-info">{{ $n_lyrics[$j]->artist }}</a>
                                            </li>
                                        </ul>
                                    </h5>
                                    <div class="slid-bottom-grids">
                                        <div class="slid-bottom-grid">
                                            <p class="author author-info"><a href="#" class="author">Admin</a></p>
                                        </div>
                                        <div class="slid-bottom-grid slid-bottom-right">
                                            <p class="views views-info">2,114,200 views</p>
                                        </div>
                                        <div class="clearfix"> </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            @php $start += 4; @endphp
                            <div class="clearfix"> </div>
                        </div>
                    </li>
                    @endfor
                </ul>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
@endsection

@push('js')
<script src="myplay/js/responsiveslides.min.js"></script>
<script>
// You can also use "$(window).load(function() {"
$(function() {
    // Slideshow 4
    $("#slider3").responsiveSlides({
        auto: true,
        pager: false,
        nav: true,
        speed: 1000,
        namespace: "callbacks",
        before: function() {
            $('.events').append("<li>before event fired.</li>");
        },
        after: function() {
            $('.events').append("<li>after event fired.</li>");
        }
    });

});
</script>

@endpush

