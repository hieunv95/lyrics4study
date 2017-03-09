<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><h1><img src="/myplay/images/logo.png" alt="" /></h1></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <div class="top-search">
                <div class="col-md-6 col-md-offset-1">
                    <form action="{{ action('Web\HomeController@search') }}" method="GET">
                        <div class="input-group">
                            <div class="input-group-btn search-panel">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span id="search_concept">All</span> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#title">Song Name</a></li>
                                    <li><a href="#artist">Artist</a></li>
                                    <li><a href="#lyrics">Lyrics</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#all">All</a></li>
                                </ul>
                            </div>
                            <input type="hidden" name="search_param" value="all" id="search_param">
                            <div class="ui-widget">
                                <input type="search" class="form-control" id="q" name="q" placeholder="Search" required>
                            </div>
                            <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="header-top-right">
                <!-- <div class="file">
                    <a href="upload.html">Upload</a>
                  </div> -->
                @if (Auth::guest())
                    <div class="signin">
                        <a href="#small-dialog2" class="play-icon popup-with-zoom-anim">Sign Up</a>
                        <!-- pop-up-box -->
                        <script type="/myplay/text/javascript" src="js/modernizr.custom.min.js"></script>
                        <link href="/myplay/css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
                        <script src="/myplay/js/jquery.magnific-popup.js" type="text/javascript"></script>
                        <!--//pop-up-box -->
                        <!-- <div id="small-dialog2" class="mfp-hide">
                          <h3>Create Account</h3>
                          <div class="social-sits">
                            <div class="facebook-button">
                              <a href="#">Connect with Facebook</a>
                            </div>
                            <div class="chrome-button">
                              <a href="#">Connect with Google</a>
                            </div>
                            <div class="button-bottom">
                              <p>Already have an account? <a href="#small-dialog" class="play-icon popup-with-zoom-anim">Login</a></p>
                            </div>
                          </div>
                          <div class="signup">
                            <form>
                              <input type="text" class="email" placeholder="Mobile Number" maxlength="10" pattern="[1-9]{1}\d{9}" title="Enter a valid mobile number" />
                              <input type="text" class="email" name="name" placeholder="Name">
                            </form>
                            <div class="continue-button">
                              <a href="#small-dialog3" class="hvr-shutter-out-horizontal play-icon popup-with-zoom-anim">CONTINUE</a>
                            </div>
                          </div>
                          <div class="clearfix"> </div>
                        </div> -->
                        <div id="small-dialog2" class="mfp-hide">
                            <h3>Create Account</h3>
                            <div class="social-sits">
                                <div class="facebook-button">
                                    <a href="#">Connect with Facebook</a>
                                </div>
                                <div class="chrome-button">
                                    <a href="#">Connect with Google</a>
                                </div>
                                <div class="button-bottom">
                                    <p>Already have an account? <a href="#small-dialog" class="play-icon popup-with-zoom-anim">Login</a></p>
                                </div>
                            </div>
                            <div class="signup">
                                <form method="POST" action="{{ url('/register') }}">
                                    {{ csrf_field() }}
                                    <input type="text" class="email" name="name" placeholder="Name" required/>
                                    <input type="text" class="email" name="email" placeholder="Email" required pattern="([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?" title="Enter a valid email"/>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                    <input id="password" type="password" class="email" name="password" placeholder="Password" required pattern=".{6,}" title="Minimum 6 characters required" autocomplete="off" />
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                    <input id="password-confirm" type="password" class="email" name="password_confirmation" placeholder="Confirm Password" required>
                                    <input type="submit"  value="Sign Up"/>
                                </form>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div id="small-dialog7" class="mfp-hide">
                            <h3>Create Account</h3>
                            <div class="social-sits">
                                <div class="facebook-button">
                                    <a href="#">Connect with Facebook</a>
                                </div>
                                <div class="chrome-button">
                                    <a href="#">Connect with Google</a>
                                </div>
                                <div class="button-bottom">
                                    <p>Already have an account? <a href="#small-dialog" class="play-icon popup-with-zoom-anim">Login</a></p>
                                </div>
                            </div>
                            <div class="signup">
                                <form action="upload.html">
                                    <input type="text" class="email" placeholder="Email" required="required" pattern="([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?" title="Enter a valid email"/>
                                    <input type="password" placeholder="Password" required="required" pattern=".{6,}" title="Minimum 6 characters required" autocomplete="off" />
                                    <input type="submit"  value="Sign In"/>
                                </form>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div id="small-dialog4" class="mfp-hide">
                            <h3>Feedback</h3>
                            <div class="feedback-grids">
                                <div class="feedback-grid">
                                    <p>Suspendisse tristique magna ut urna pellentesque, ut egestas velit faucibus. Nullam mattis lectus ullamcorper dui dignissim, sit amet egestas orci ullamcorper.</p>
                                </div>
                                <div class="button-bottom">
                                    <p><a href="#small-dialog" class="play-icon popup-with-zoom-anim">Sign in</a> to get started.</p>
                                </div>
                            </div>
                        </div>
                        <div id="small-dialog5" class="mfp-hide">
                            <h3>Help</h3>
                            <div class="help-grid">
                                <p>Suspendisse tristique magna ut urna pellentesque, ut egestas velit faucibus. Nullam mattis lectus ullamcorper dui dignissim, sit amet egestas orci ullamcorper.</p>
                            </div>
                            <div class="help-grids">
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog4" class="play-icon popup-with-zoom-anim">Feedback</a></p>
                                </div>
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog6" class="play-icon popup-with-zoom-anim">Lorem ipsum dolor sit amet</a></p>
                                </div>
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog6" class="play-icon popup-with-zoom-anim">Nunc vitae rutrum enim</a></p>
                                </div>
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog6" class="play-icon popup-with-zoom-anim">Mauris at volutpat leo</a></p>
                                </div>
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog6" class="play-icon popup-with-zoom-anim">Mauris vehicula rutrum velit</a></p>
                                </div>
                                <div class="help-button-bottom">
                                    <p><a href="#small-dialog6" class="play-icon popup-with-zoom-anim">Aliquam eget ante non orci fac</a></p>
                                </div>
                            </div>
                        </div>
                        <div id="small-dialog6" class="mfp-hide">
                            <div class="video-information-text">
                                <h4>Video information & settings</h4>
                                <p>Suspendisse tristique magna ut urna pellentesque, ut egestas velit faucibus. Nullam mattis lectus ullamcorper dui dignissim, sit amet egestas orci ullamcorper.</p>
                                <ol>
                                    <li>Nunc vitae rutrum enim. Mauris at volutpat leo. Vivamus dapibus mi ut elit fermentum tincidunt.</li>
                                    <li>Nunc vitae rutrum enim. Mauris at volutpat leo. Vivamus dapibus mi ut elit fermentum tincidunt.</li>
                                    <li>Nunc vitae rutrum enim. Mauris at volutpat leo. Vivamus dapibus mi ut elit fermentum tincidunt.</li>
                                    <li>Nunc vitae rutrum enim. Mauris at volutpat leo. Vivamus dapibus mi ut elit fermentum tincidunt.</li>
                                    <li>Nunc vitae rutrum enim. Mauris at volutpat leo. Vivamus dapibus mi ut elit fermentum tincidunt.</li>
                                </ol>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('.popup-with-zoom-anim').magnificPopup({
                                    type: 'inline',
                                    fixedContentPos: false,
                                    fixedBgPos: true,
                                    overflowY: 'auto',
                                    closeBtnInside: true,
                                    preloader: false,
                                    midClick: true,
                                    removalDelay: 300,
                                    mainClass: 'my-mfp-zoom-in'
                                });

                            });
                        </script>
                    </div>
                    <div class="signin">
                        <a href="#small-dialog" class="play-icon popup-with-zoom-anim">Sign In</a>
                        <div id="small-dialog" class="mfp-hide">
                            <h3>Login</h3>
                            <div class="social-sits">
                                <div class="facebook-button">
                                    <a href="{{ action('Auth\SocialAuthController@redirect', [
                                        'provider' => config('custom.provider.facebook')
                                        ]) }}">Connect with Facebook
                                    </a>
                                </div>
                                <div class="chrome-button">
                                    <a href="{{ action('Auth\SocialAuthController@redirect', [
                                        'provider' => config('custom.provider.google')
                                        ]) }}">Connect with Google
                                    </a>
                                </div>
                                <div class="button-bottom">
                                    <p>New account? <a href="#small-dialog2" class="play-icon popup-with-zoom-anim">Signup</a></p>
                                </div>
                            </div>
                            <div class="signup">
                                <form method="POST" action="{{ url('/login') }}">
                                    {{ csrf_field() }}
                                    <input type="text" class="email" name="email" placeholder="Enter email / mobile" required="required" pattern="([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?"/>
                                    <input type="password" name="password" placeholder="Password" required="required" pattern=".{6,}" title="Minimum 6 characters required" autocomplete="on" />
                                    <input type="submit"  value="LOGIN"/>
                                </form>
                                <div class="forgot">
                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot password ?</a>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                @else
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <button class="btn btn-info dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </button>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#">Profile</a>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endif
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</nav>
