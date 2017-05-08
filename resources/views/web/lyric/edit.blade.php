@extends('layouts.web.myplay')
@push('css')
<style>
    .form-section {
        padding-left: 1em;
        font-size: initial;
    }
    .recommended {
        width: 60%;
    }
    .input-group-btn {
        position: relative;
        white-space: nowrap;
    }
</style>
@endpush
@section('content')
<div class="main-grids">
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
                <h3>Edit Lyrics</h3>
            </div>
            {{-- @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <div class="form-section">
                {{ Form::open([
                    'url' => action('Web\LyricController@update', $lyric->id),
                    'method' => 'PUT',
                    'class' => 'form form-create-lyrics form-horizontal',
                    'files' => 'true',
                ]) }}
                    <div class="{{ Form::showErrClass('title') }}">
                        {{ Form::label('title', 'Title', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::text('title', $lyric->title, [
                            'class' => 'form-control'
                        ]) }}
                        {{ Form::showErrField('title') }}
                    </div>
                    <div class="{{ Form::showErrClass('artist') }}">
                        {{ Form::label('artist', 'Artist', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::text('artist', $lyric->artist, [
                            'class' => 'form-control'
                        ]) }}
                        {{ Form::showErrField('artist') }}
                    </div>
                    <div class="{{ Form::showErrClass('yt_video_id') }}">
                        {{ Form::label('yt_link', 'Youtube Link', [
                            'class' => 'control-label'
                        ]) }}
                        {{-- <div class="input-group"> --}}
                            {{ Form::text('yt_link', $ytLink, [
                                'class' => 'form-control'
                            ]) }}
                            <!-- <span class="input-group-btn">
                                {{ Form::button('Check link', [
                                    'class' => 'btn btn-info btn-check-yt-link'
                                ]) }}
                            </span> -->
                        {{-- </div> --}}
                        {{ Form::showErrField('yt_video_id') }}
                    </div>
                    <div class="{{ Form::showErrClass('lyric_file') }}">
                        {{ Form::label('lyric_file', 'Lyric File', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::file('lyric_file', null, [
                            'class' => 'form-control'
                        ]) }}
                        {{ Form::showErrField('lyric_file') }}
                    </div>
                    <div class="form-group">
                        {{ Form::submit('Update', [
                            'class' => 'btn btn-info btn-save-lyrics'
                        ]) }}
                        <a href="{{ action('Web\UserController@showLyrics' , Auth::id()) }}"
                            class="btn btn-warning btn-cancel-create">Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
