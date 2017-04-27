@extends('layouts.web.myplay')

@section('content')
	<div class="main-grids">
		<div class="recommended">
			<div class="recommended-grids">
				<div class="recommended-info">
				    <h3>Results for "{{ $search }}"</h3>
				</div>
				{{--  @php
				    $count = 0;
				@endphp --}}
				@if ($list->isEmpty())
			        <div class="col-md-8 resent-grid recommended-grid" style="height: 204px;">
			        	<h4>No results for "{{ $search }}"</h4>
			        </div>
		        @else
					@include('web.lyric._list')
		        @endif
		        <div class="clearfix"> </div>
			</div>
		</div>
	</div>
@endsection
