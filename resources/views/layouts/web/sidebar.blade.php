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
            <li class="active">
                <a href="{{ url('/') }}" class="home-icon">
                    <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    Home
                </a>
            </li>
            <li>
                <a href="#" class="song-icon">
                    <span class="glyphicon glyphicon-music" aria-hidden="true"></span>
                    Top Songs
                </a>
            </li>
            <li>
                <a href="#" class="song-icon">
                    <span class="glyphicon glyphicon-music" aria-hidden="true"></span>
                    New Songs
                </a>
            </li>
            @if (Auth::check())
            <li>
                <a href="{{ action('Web\UserController@history') }}" class="song-icon">
                    <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                    &emsp;&emsp;&emsp;History
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
