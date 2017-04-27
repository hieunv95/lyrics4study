@extends('layouts.web.myplay')

@section('content')
<style type="text/css">
    .panel {
        font-size: medium;
    }
</style>
<div class="panel-group">
    <div class="col-sm-8 single-left">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4>Detail history</h4></div>
            <div class="panel-body">
                <ul>
                    <li>
                        Song:
                        <a href="{{ route('show', [
                            'id' => $lyrics[0]->id,
                            'title' => str_slug($lyrics[0]->title,'-'),
                            'artist' => str_slug($lyrics[0]->artist,'-'),
                        ])}}">
                            <b>{{ $lyrics[0]->title }}</b>
                        </a>
                    </li>
                    <li>
                        Artist:
                       <b>{{ $lyrics[0]->artist }}</b>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Score</th>
                            <th>Level</th>
                            <th>Played at</th>
                        </tr>
                        @foreach ($lyrics as $lyric)
                        <tr>
                            <td>{{ $lyric->pivot->score }}</td>
                            <td>{{ levelToString($lyric->pivot->level) }}</td>
                            <td>{{ $lyric->pivot->created_at->format('F jS, Y - g:i:s A') }}</td>
                        </tr>
                        @endforeach
                    </table>
                    {{ $lyrics->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
@endsection
