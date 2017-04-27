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
</style>
@endpush
@section('content')
<div class="main-grids">
    <div class="recommended">
        <div class="recommended-grids">
            <div class="recommended-info">
                <h3>Create Lyrics Form</h3>
            </div>
            <div class="form-section">
                {{ Form::open([
                    'action' => 'Web\LyricController@save',
                    'method' => 'POST',
                    'class' => 'form form-create-lyrics form-horizontal'
                ]) }}
                    <div class="form-group">
                        {{ Form::label('title', 'Title', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::text('title', '', [
                            'class' => 'form-control'
                        ]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('artist', 'Artist', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::text('artist', '', [
                            'class' => 'form-control'
                        ]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('yt_link', 'Youtube Link', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::text('yt_link', '', [
                            'class' => 'form-control'
                        ]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('lyric_file', 'Lyric File', [
                            'class' => 'control-label'
                        ]) }}
                        {{ Form::file('lyric_file', null, [
                            'class' => 'form-control'
                        ]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::submit('Save', [
                            'class' => 'btn btn-info'
                        ]) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
