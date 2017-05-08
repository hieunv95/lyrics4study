{{-- {{ dd($list) }} --}}
@push('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/bower_components/sweetalert/dist/sweetalert.css') }}">
@endpush
@foreach ($list as $lyric)
<div class="col-md-3 resent-grid recommended-grid">
    <div class="resent-grid-img recommended-grid-img">
        <a href="{{ route('show', ['id' => $lyric->id,'title' => str_slug($lyric->title,'-'),
            'artist' => str_slug($lyric->artist,'-')]) }}">
            <img src="https://i3.ytimg.com/vi/{{ $lyric->link_id }}/hqdefault.jpg"
            alt="{{ $lyric->title }}-{{ $lyric->artist }}" />
        </a>
        {{-- <div class="time small-time">
            <p>3:04</p>
        </div>
        <div class="clck small-clck">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
        </div> --}}
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
                <p class="author author-info"><a href="{{ action('Web\UserController@showLyrics' , $lyric->user_id) }}"
                    class="author">{{ $lyric->user->name }}</a></p>
            </li>
            <li class="right-list">
            @can('update', $lyric)
                <a href="{{ action("Web\LyricController@edit", $lyric->id) }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
            @endcan
            @can('delete', $lyric)
                {{ Form::open([
                    'url' => action("Web\LyricController@delete", $lyric->id),
                    'method' => 'DELETE',
                    'style' => "display: none;",
                    'id' => 'delete_lyric_form_' . $loop->iteration,
                ]) }}
                {{ Form::close() }}
                <a href="#" class="btn-delete-lyric" data-id="{{ $loop->iteration }}">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            @endcan
                <p class="views views-info">{{ $lyric->viewed }} views</p>
            </li>
        </ul>
    </div>
</div>
@endforeach
@push('js')
    <script src="{{ asset('/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        $('.btn-delete-lyric').click(function (e) {
            e.preventDefault();
            var form = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this lyrics!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true,
            },
            function() {
                $('#delete_lyric_form_' + form).submit();
            });
        });
    });
    </script>
@endpush
