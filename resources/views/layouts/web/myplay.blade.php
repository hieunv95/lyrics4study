<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>{{ config('app.name', 'Lyrics4Study') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Lyrics4Study - Learning English through lyrics" />
@stack('meta')
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap -->
<link href="/myplay/css/bootstrap.min.css" rel='stylesheet' type='text/css' media="all" />
<!-- //bootstrap -->
<link href="/myplay/css/dashboard.css" rel="stylesheet">
<!-- Custom Theme files -->
<link href="/myplay/css/style.css" rel='stylesheet' type='text/css' media="all" />
<link rel="stylesheet" href="/css/jquery-ui.min.css" type="text/css" />
<script src="/myplay/js/jquery-1.11.1.min.js"></script>
<!--start-smoth-scrolling-->
<!-- fonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
<!-- //fonts -->
<style>
    .ui-widget {
        font-size: 1.5em;
    }
    .btn-user-name {
        background-color: Transparent;
        background-repeat:no-repeat;
        border: none;
        cursor: pointer;
        overflow: hidden;
        outline: none;
        font-weight: bold;
    }
    .glyphicon-lyrics {
        margin: 0 2em 0 0;
        font-size: 16px !important;
    }
    .ui-autocomplete {
        z-index: 1031 !important;
    }
</style>
<script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
</script>
@stack('css')
</head>
<body>
@include('layouts.web.header')
@include('layouts.web.sidebar')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="content">
            @yield('content')
        </div>
        @include('layouts.web.footer')
    </div>
    <div class="clearfix"> </div>
    <div class="drop-menu">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu4">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Regular link</a></li>
          <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">Disabled link</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another link</a></li>
        </ul>
    </div>

<script src="/myplay/js/bootstrap.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/laroute.js"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var selector = '.nav li';
        var loc = window.location.href.split('/');
        var page = loc[loc.length - 1];
        $('ul.nav-sidebar a').each(function (i) {
            var href = $(this).attr('href');
            if (href.indexOf(page) !== -1) {
                $('ul.nav li.active').removeClass('active');
                $(this).parent().addClass('active');
            }
        });
        window.fbAsyncInit = function() {
            FB.init({
              appId      : 1226236657472789,
              xfbml      : true,
              version    : 'v2.8'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        var $f = $('.footer').outerHeight(true);
        $('.content').css('min-height', "calc(100vh - " + $f + "px)");
        $("#q").autocomplete({
            source: "{{ action('Web\HomeController@autocomplete') }}",
            minLength: 3,
            select: function (event, ui) {
                //$('#q').val(ui.item.value);
                window.location.href = ui.item.href;
            }
        });
/*        .autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
            .append( "<div>" + item.href + "</div>" )
                // .append( "<a href='" + item.href + "'></a><br>" )
                .appendTo( ul );
        };*/

        $('.search-panel .dropdown-menu').find('a').click(function (e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });
        $('.fb-share-lyric').click(function() {
            FB.ui({
                method: 'share',
                display: 'popup',
                href: $(this).data('url'),
                picture: $('.song-thumbnail').val(),
                title: $('.song-meta').html(),
                description: 'Lyrics4Study - Learning English with lyrics',
            }, function(response){});
        });
    });
</script>
@stack('js')
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
  </body>
</html>
