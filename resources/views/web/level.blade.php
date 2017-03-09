@extends('layouts.web.myplay')

@section('content')
<div class="main-grids">
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
                <h3>Choose level you want to play:</h3><br></br>
                <a href="{{ action('Web\LyricController@play', [
                    'artist' => $artist,
                    'title' => $title,
                    'id' => $id,
                    'level' => 'easy',
                ]) }}" type="button" class="btn btn-lg btn-block btn-info">
                    Easy
                </a>
                <a href="{{ action('Web\LyricController@play', [
                    'artist' => $artist,
                    'title' => $title,
                    'id' => $id,
                    'level' => 'medium',
                ]) }}" type="button" class="btn btn-lg btn-block btn-warning">
                    Medium
                </a>
                <a href="{{ action('Web\LyricController@play', [
                    'artist' => $artist,
                    'title' => $title,
                    'id' => $id,
                    'level' => 'hard',
                ]) }}" type="button" class="btn btn-lg btn-block btn-danger">
                    Hard
                </a>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
@endsection
