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
            <div class="panel-heading"><h4>Answers</h4></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Your answers</th>
                                <th>Right answers</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i< $num_words; $i++)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $usr_ans[$i] }}</td>
                                    <td>{{ $ans[$i] }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
@endsection
