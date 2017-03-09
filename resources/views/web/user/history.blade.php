@extends('layouts.web.myplay')

@section('content')
<style type="text/css">
    table {
        font-size: medium;
    }
</style>
<div class="panel-group">
    <div class="col-sm-8 single-left">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4>History</h4></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Song</th>
                            <th>Artist</th>
                            <th>Score</th>
                            <th>Total</th>
                            <th>Played at</th>
                        </tr>
                        @foreach ($lyrics as $lyric)
                        <tr>
                            <td>
                                <a href="{{ route('show', [
                                    'id' => $lyric->id,
                                    'title' => str_slug($lyric->title,'-'),
                                    'artist' => str_slug($lyric->artist,'-'),
                                ])}}">
                                    {{ $lyric->title }}
                                </a>
                            </td>
                            <td>{{ $lyric->artist }}</td>
                            <td>{{ $lyric->pivot->score }}</td>
                            <td>{{ $lyric->pivot->total_word }}</td>
                            <td>{{ $lyric->pivot->created_at->format('d-m-Y H:i:s') }}</td>
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



