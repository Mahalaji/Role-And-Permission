<!DOCTYPE html>
<!--
	24 News by FreeHTML5.co
	Twitter: https://twitter.com/fh5co
	Facebook: https://fb.com/fh5co
	URL: https://freehtml5.co
-->
<html lang="en" class="no-js">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link href="css/frontend/media_query.css" rel="stylesheet" type="text/css" />
    <link href="css/frontend/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="css/frontend/animate.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="css/frontend/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/frontend/owl.theme.default.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    <link href="css/frontend/style_1.css" rel="stylesheet" type="text/css" />
    <!-- Modernizr JS -->
    <script src="js/frontend/modernizr-3.5.0.min.js"></script>
</head>

<body>
@include('frontend.layouts.header')
    <div class="container-fluid paddding mb-5">
        <div class="row mx-0">
            @foreach($news->take(1) as $newss)
                <div class="col-md-6 col-12 paddding animate-box" data-animate-effect="fadeIn">
                    <div class="fh5co_suceefh5co_height"><img src="{{ asset($newss->news_image) }}" alt="img" style="object-fit: cover;" />
                        <div class="fh5co_suceefh5co_height_position_absolute"></div>
                        <div class="fh5co_suceefh5co_height_position_absolute_font">
                            <div class=""><a href="#" class="color_fff">&nbsp;&nbsp;News
                                </a></div>
                            <div class=""><a href="{{ url('/News/' . $newss->slug) }}"
                                    class="fh5co_good_font">{{$newss->title}}</a></div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-6">
                <div class="row">
                    @foreach($users->take(4) as $row)
                        <div class="col-md-6 col-6 paddding animate-box" data-animate-effect="fadeIn">
                            <div class="fh5co_suceefh5co_height_2"><img src="{{ asset($row->image) }}" alt="img"
                                    style="object-fit: cover;" />
                                <div class="fh5co_suceefh5co_height_position_absolute"></div>
                                <div class="fh5co_suceefh5co_height_position_absolute_font_2">
                                    <div class=""><a href="#" class="color_fff">&nbsp;&nbsp;Blogs</a></div>
                                    <div class=""><a href="{{ url('/Blogs/' . $row->slug) }}"
                                            class="fh5co_good_font_2">{{$row->title}}</a></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-3">
        <div class="container animate-box" data-animate-effect="fadeIn">
            <div>
                <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">Blogs</div>
            </div>
            <div class="owl-carousel owl-theme js" id="slider1">
                @foreach($users as $row)
                    <div class="item px-2">
                        <div class="fh5co_latest_trading_img_position_relative">
                            <div class="fh5co_latest_trading_img"><img src="{{ asset($row->image) }}" alt=""
                                    class="fh5co_img_special_relative" style="object-fit: cover;" /></div>
                            <div class="fh5co_latest_trading_img_position_absolute"></div>
                            <div class="fh5co_latest_trading_img_position_absolute_1">
                                <a href="{{ url('/Blogs/' . $row->slug) }}" class="text-white">{{$row->title}}</a>
                                <div class="fh5co_latest_trading_date_and_name_color">{{$row->name}} - {{$row->created_at}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container-fluid pb-4 pt-5">
        <div class="container animate-box">
            <div>
                <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">News</div>
            </div>
            <div class="owl-carousel owl-theme" id="slider2">
                @foreach($news as $newss)
                    <div class="item px-2">
                        <div class="fh5co_hover_news_img">
                            <div class="fh5co_news_img">
                                <img src="{{ asset($newss->news_image) }}" alt="" style="object-fit: cover;" />
                            </div>
                            <div>
                                <a href="{{ url('/News/' . $newss->slug) }}" class="d-block fh5co_small_post_heading">
                                    <span>{{$newss->title}}</span>
                                    <div class="c_g"><i class="fa fa-clock-o"></i>{{$newss->created_at}}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="container-fluid fh5co_video_news_bg pb-4">
            <div class="container animate-box" data-animate-effect="fadeIn">
                <div>
                    <div class="fh5co_heading fh5co_heading_border_bottom pt-5 pb-2 mb-4  text-white">Video News</div>
                </div>
                <div>
                    <div class="owl-carousel owl-theme" id="slider3">
                        <div class="item px-2">
                            <div class="fh5co_hover_news_img">
                                <div class="fh5co_hover_news_img_video_tag_position_relative">
                                    <div class="fh5co_news_img">
                                        <iframe id="video" width="100%" height="200"
                                            src="https://www.youtube.com/embed/Km2gpK8xuVA?" frameborder="0"
                                            allow="encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute fh5co_hide">
                                        <img src="images/ariel-lustre-208615.jpg" alt="" />
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute_1 fh5co_hide"
                                        id="play-video">
                                        <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button_1">
                                            <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button">
                                                <span><i class="fa fa-play"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item px-2">
                            <div class="fh5co_hover_news_img">
                                <div class="fh5co_hover_news_img_video_tag_position_relative">
                                    <div class="fh5co_news_img">
                                        <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/Paipo4d1SK0" frameborder="0"
                                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute fh5co_hide_2">
                                        <img src="images/39-324x235.jpg" alt="" />
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute_1 fh5co_hide_2"
                                        id="play-video_2">
                                        <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button_1">
                                            <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button">
                                                <span><i class="fa fa-play"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item px-2">
                            <div class="fh5co_hover_news_img">
                                <div class="fh5co_hover_news_img_video_tag_position_relative">
                                    <div class="fh5co_news_img">
                                        <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/oBTKDPKPoZ8" frameborder="0"
                                            allow="autoplay; encrypted-media" allowfullscreen></iframe>

                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute fh5co_hide_3">
                                        <img src="images/joe-gardner-75333.jpg" alt="" />
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute_1 fh5co_hide_3"
                                        id="play-video_3">
                                        <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button_1">
                                            <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button">
                                                <span><i class="fa fa-play"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="item px-2">
                            <div class="fh5co_hover_news_img">
                                <div class="fh5co_hover_news_img_video_tag_position_relative">
                                    <div class="fh5co_news_img">
                                        <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/h9Z4oGN89MU" frameborder="0"
                                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute fh5co_hide_4">
                                        <img src="images/vil-son-35490.jpg" alt="" />
                                    </div>
                                    <div class="fh5co_hover_news_img_video_tag_position_absolute_1 fh5co_hide_4"
                                        id="play-video_4">
                                        <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button_1">
                                            <div class="fh5co_hover_news_img_video_tag_position_absolute_1_play_button">
                                                <span><i class="fa fa-play"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('frontend.layouts.footer')

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/frontend/owl.carousel.min.js"></script>
        <!--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
            integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
            crossorigin="anonymous"></script>
        <!-- Waypoints -->
        <script src="js/frontend/jquery.waypoints.min.js"></script>
        <!-- Main -->
        <script src="js/frontend/main.js"></script>

</body>

</html>