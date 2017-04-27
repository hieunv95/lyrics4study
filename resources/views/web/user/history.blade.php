@extends('layouts.web.myplay')

@section('content')
<style type="text/css">
    table {
        font-size: medium;
    }
</style>
<div class="panel-group">
    <div class="col-sm-10 single-left">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4>History</h4></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Song</th>
                            <th>Artist</th>
                            <th>Played Times</th>
                            <th>Last time played</th>
                        </tr>
                        @foreach ($lyrics as $id => $lyric)
                        <tr>
                            <td>
                                <a href="{{ route('show', [
                                    'id' => $id,
                                    'title' => str_slug($lyric[0]->title,'-'),
                                    'artist' => str_slug($lyric[0]->artist,'-'),
                                ])}}">
                                    <b>{{ $lyric[0]->title }}</b>
                                </a>
                            </td>
                            <td><b>{{ $lyric[0]->artist }}</b></td>
                            <td>
                                <a href="{{ action('Web\UserController@detailHistory', $id) }}">
                                    {{ $lyric->count() }}
                                </a>
                            </td>
                            <td>{{ $lyric->last()->pivot->created_at->format('F jS, Y g:i:s A') }}</td>
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



