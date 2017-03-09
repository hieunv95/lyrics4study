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
				@if ($results->isEmpty())
			        <div class="col-md-8 resent-grid recommended-grid" style="height: 204px;">
			        	<h4>No results for "{{ $search }}"</h4>
			        </div>
		        @else
				@foreach ($results as $lyric)
		        <div class="col-md-3 resent-grid recommended-grid">
		            <div class="resent-grid-img recommended-grid-img">
		                <a href="{{ route('show', ['id' => $lyric->id,'title' => str_slug($lyric->title,'-'),
		                	'artist' => str_slug($lyric->artist,'-')]) }}">
		                	<img src="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg"
		                	alt="{{ $lyric->title }}-{{ $lyric->artist }}" />
		                </a>
		                <div class="time small-time">
		                    <p>3:04</p>
		                </div>
		                <div class="clck small-clck">
		                    <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
		                </div>
		            </div>
		            <div class="resent-grid-info recommended-grid-info video-info-grid">
		                <h5>
		                    <ul>
		                        <li><a href="{{ route('show', ['id' => $lyric->id,'title' => str_slug($lyric->title,'-'),
			                         'artist' => str_slug($lyric->artist,'-')]) }}"
			                         class="title title-info">{{ $lyric->title }}</a></li>
		                        <li><a href="#" class="artist artist-info">{{ $lyric->artist }}</a></li>
		                    </ul>
		                </h5>
		                <ul>
		                    <li>
		                        <p class="author author-info"><a href="#" class="author">Admin</a></p>
		                    </li>
		                    <li class="right-list">
		                        <p class="views views-info">200 views</p>
		                    </li>
		                </ul>
		            </div>
		        </div>
		        {{--  @php
		        	$count++;
		        @endphp
		        @if ($count % 4 == 0)
		           <div class="recommended-grids">
		           @if ($count > 4)
		               </div>
		            @endif
		           <div class="clearfix"> </div>
		        @endif--}}
		        @endforeach
		        @endif
		        <div class="clearfix"> </div>
			</div>
		</div>
	</div>
@endsection
