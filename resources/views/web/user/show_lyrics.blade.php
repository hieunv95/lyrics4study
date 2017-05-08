@extends('layouts.web.myplay')

@section('content')
<div class="main-grids">
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
                <h3>Lyrics by {{ $userName }}</h3>
            </div>
            @include('web.lyric._list')
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
@endsection
