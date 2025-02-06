<!DOCTYPE html>
<html lang="en" class="no-js">
<style>
    a {
        color: black !important;
        text-decoration: none !important;
    }
</style>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="/css/frontend/media_query.css" rel="stylesheet" type="text/css" />
    <link href="/css/frontend/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="/css/frontend/animate.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="/css/frontend/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="/css/frontend/owl.theme.default.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    <link href="/css/frontend/style_1.css" rel="stylesheet" type="text/css" />
    <!-- Modernizr JS -->
    <script src="/js/frontend/modernizr-3.5.0.min.js"></script>
</head>

<body class="single">
    @include('frontend.layouts.header', ['users' => $blogs, 'news' => $news])
    <div id="fh5co-title-box"
        style="background-image: url('{{ asset(htmlspecialchars($blog->image)) }}');">
        <div class="overlay"></div>
        <div class="page-title">
            <h6>{{$blog->created_at}}</h6>
            <h2>{{$blog->title}}</h2>
        </div>
    </div>
    <div id="fh5co-single-content" class="container-fluid pb-4 pt-4 paddding">
        <div class="container paddding">
            <div class="row mx-0">
                <div class="col-md-8 animate-box" data-animate-effect="fadeInLeft">
                    <p>
                        {{$blog->description}}
                    </p>
                </div>
                <div class="col-md-3 animate-box" data-animate-effect="fadeInRight">
                    <div>
                        <div class="fh5co_heading fh5co_heading_border_bottom pt-3 py-2 mb-4">Related Blogs</div>
                    </div>
                    @foreach ($related_blogs as $row)
                        <a href="{{ url('/Blogs/' . $row->slug) }}">
                            <div class="row pb-3">
                                <div class="col-5 align-self-center">
                                    <img src="{{ asset($row->image) }}" alt="img" class="fh5co_most_trading" style="object-fit: cover;"/>
                                </div>
                                <div class="col-7 paddding">
                                    <div class="most_fh5co_treding_font"> {{ $row['title'] }}
                                    </div>
                                    <div class="most_fh5co_treding_font_123">{{ $row['created_at'] }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pb-4 pt-5">
        <div class="container animate-box">
            <div>
                <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">Trending</div>
            </div>
            <div class="owl-carousel owl-theme" id="slider2">
                @foreach($blogs->take(5) as $row)
                    <div class="item px-2">
                        <div class="fh5co_hover_news_img">
                            <div class="fh5co_news_img"><img src="{{ asset($row->image) }}" alt="" style="object-fit: cover;" /></div>
                            <div>
                                <a href="{{ url('/Blogs/' . $row->slug) }}" class="d-block fh5co_small_post_heading"><h6
                                        class="">{{$row->title}}</h6></a>
                                <div class="c_g"><i class="fa fa-clock-o"></i>{{$row->created_at}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('frontend.layouts.footer', ['categories'=>$categories,'blogcategory'=>$blogcategory,'blogmodify' => $blogmodify, 'news' => $news,'newsmodify'=>$newsmodify,'users' => $blogs])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/frontend/owl.carousel.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>
    <!-- Waypoints -->
    <script src="/js/frontend/jquery.waypoints.min.js"></script>
    <!-- Parallax -->
    <script src="/js/frontend/jquery.stellar.min.js"></script>
    <!-- Main -->
    <script src="/js/frontend/main.js"></script>
    <script>if (!navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)) { $(window).stellar(); }</script>

</body>

</html>