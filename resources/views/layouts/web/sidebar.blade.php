<div class="col-sm-3 col-md-2 sidebar">
    <div class="top-navigation">
        <div class="t-menu">MENU</div>
        <div class="t-img">
            <img src="/myplay/images/lines.png" alt="" />
        </div>
        <div class="clearfix"> </div>
    </div>
    <div class="drop-navigation drop-navigation">
        <ul class="nav nav-sidebar">
            <li>
                <a href="{{ url('home') }}" class="home-icon">
                    <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    Home
                </a>
            </li>
            <li>
                <a href="{{ action('Web\HomeController@showTopLyrics') }}" class="song-icon">
                    <span class="glyphicon glyphicon-fire glyphicon-lyrics" aria-hidden="true"></span>
                    Top Lyrics
                </a>
            </li>
            <li>
                <a href="{{ action('Web\HomeController@showNewLyrics') }}" class="song-icon">
                    <span class="glyphicon glyphicon-star glyphicon-lyrics" aria-hidden="true"></span>
                    New Lyrics
                </a>
            </li>
            @if (Auth::check())
            <hr align="center" width="80%">
            <li>
                <a href="{{ action('Web\UserController@showLyrics', Auth::id()) }}" class="sub-icon">
                    <span class="glyphicon glyphicon-user glyphicon-lyrics" aria-hidden="true"></span>
                    My Lyrics
                </a>
            </li>
            <li>
                <a href="{{ action('Web\LyricController@create') }}" class="sub-icon">
                    <span class="glyphicon glyphicon-cloud-upload glyphicon-lyrics" aria-hidden="true"></span>
                    Upload
                </a>
            </li>
            <li>
                <a href="{{ action('Web\UserController@history') }}" class="sub-icon">
                    <span class="glyphicon glyphicon-home glyphicon-hourglass" aria-hidden="true"></span>
                    History
                </a>
            </li>
            @endif
        </ul>
        <!-- script-for-menu -->
        <script>
            $( ".top-navigation" ).click(function() {
                $( ".drop-navigation" ).slideToggle( 300, function() {
                    // Animation complete.
                });
            });
        </script>
        <div class="side-bottom">
            <div class="side-bottom-icons">
                <ul class="nav2">
                    <li>
                        <a href="#" class="facebook twitter fb-share-lyric" data-url="{{ Request::fullUrl() }}">
                        </a>
                    </li>
                    <li><a href="#" class="facebook"> </a></li>
                    <li><a href="#" class="facebook chrome"> </a></li>
                </ul>
            </div>
            <div class="copyright">
                <p>Copyright © 2017. All Rights Reserved <a href="http://w3layouts.com/"></a></p>
            </div>
        </div>
    </div>
</div>
