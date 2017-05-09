@extends('layouts.web.myplay')

@push('css')
    <style>
        .alert-lyric-status {
            font-size: medium;
            display:inline-block;
            clear :both;
        }
    </style>
@endpush

@section('content')
<div class="main-grids">
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
            @if (session('status'))
                <div class="alert alert-success alert-dismissable alert-lyric-status">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session('status') }}
                </div>
            @endif
                <h3>Lyrics by {{ $userName }}</h3>
            </div>
            @include('web.lyric._list')
        </div>
    </div>
</div>
@endsection
