@extends('layouts.web.myplay')

@section('content')
    <div class="main-grids">
        <div class="recommended">
            <div class="recommended-grids">
                <div class="recommended-info">
                    <h3>New Lyrics</h3>
                </div>
                @include('web.lyric._list')
            </div>
        </div>
    </div>
@endsection
