@extends('layouts.web.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">

                    You are logged in!
                    
                    {!! Form::open(['method' => 'POST', 'class' => 'form-horizontal']) !!} 
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('user', 'Input label') !!}
                        {!! Form::text('user', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('email') }}</small>
                    </div>
                    <div class="btn-group pull-right">
                        {!! Form::reset("Reset", ['class' => 'btn btn-warning']) !!}
                        {!! Form::submit("Add", ['class' => 'btn btn-success']) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    </div>
                    
                    {!! Form::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
