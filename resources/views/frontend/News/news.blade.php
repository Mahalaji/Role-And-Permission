<!DOCTYPE html>
<html lang="en" class="no-js">

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
    <style>
        a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    @include('frontend.layouts.header', ['users' => $blog, 'news' => $newss])
    <div class="container-fluid pb-4 pt-4 paddding">
        <div class="container paddding">
            <div class="row mx-0">
                <div class="col-md-8 animate-box" data-animate-effect="fadeInLeft">
                    <div>
                        <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">News</div>
                    </div>
                    @foreach ($newss as $row)
                        <div class="row pb-4">
                            <div class="col-md-5">
                                <div class="fh5co_hover_news_img">
                                    <div class="fh5co_news_img"><img src="{{ asset($row->news_image) }}" alt="" style="object-fit: cover;"/>
                                    </div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="col-md-7 animate-box">
                                <a href="{{ url('/News/' . $row->slug) }}"
                                    class="fh5co_magna py-2">{{ $row->title }}<br></a> <a
                                    href="{{ url('/News/' . $row->slug) }}"
                                    class="fh5co_mini_time py-3">{{ $row->name }} -
                                    {{ $row->created_at }}</a>
                                <div class="fh5co_consectetur">{{ $row->description }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-3 animate-box" data-animate-effect="fadeInRight">
                    <div>
                        <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">Category</div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="fh5co_tags_all">
                        @foreach ($categories as $row)
                            <a href="#" class="category-link fh5co_tagg"
                                data-category="{{ $row->id }}">{{ $row->title }}</a>
                        @endforeach
                    </div>
                    <div>
                        <div class="fh5co_heading fh5co_heading_border_bottom pt-3 py-2 mb-4">Most Popular</div>
                    </div>
                    @foreach ($newss->take(3) as $row)
                        <div class="row pb-3">
                            <a href="{{ url('/News/' . $row->slug) }}" class="col-5 align-self-center">
                                <img src="{{ asset($row->news_image) }}" alt="img" class="fh5co_most_trading" style="object-fit: cover;" />
                            </a>
                            <div class="col-7 paddding">
                                <a href="{{ url('/News/' . $row->slug) }}"
                                    class="most_fh5co_treding_font">{{ $row->title }}
                                </a>
                                <div class="most_fh5co_treding_font_123">{{ $row->created_at }}</div>
                            </div>
                        </div>
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
                @foreach ($newss->take(4) as $row)
                    <div class="item px-2">
                        <div class="fh5co_hover_news_img">
                            <div class="fh5co_news_img"><img src="{{ asset($row->news_image) }}" alt="" style="object-fit: cover;"/></div>
                            <div>
                                <a href="{{ url('/News/' . $row->slug) }}" class="d-block fh5co_small_post_heading">
                                    <h6 class="">{{ $row->title }}</h6>
                                </a>
                                <div class="c_g"><i class="fa fa-clock-o"></i>{{ $row->created_at }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('frontend.layouts.footer', ['categories'=>$categories,'blogcategory'=>$blogcategory,'blogmodify' => $blogmodify, 'news' => $newss,'newsmodify'=>$newsmodify,'users' => $blog])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/frontend/owl.carousel.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous">
    </script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous">
    </script>
    <!-- Waypoints -->
    <script src="/js/frontend/jquery.waypoints.min.js"></script>
    <!-- Main -->
    <script src="/js/frontend/main.js"></script>
    <script>
    $(".category-link").on("click", function(e) {
    e.preventDefault();
    const categoryId = $(this).data("category");

    // Find the news container
    const newsContainer = $(".col-md-8.animate-box");

    // Show loading state
    newsContainer.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');

    // Make AJAX request
    $.ajax({
        url: '/ajaxnews/category',
        type: 'GET',
        data: { category_id: categoryId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const news = response.data;
                let newsHtml = `
                    <div>
                        <div class="fh5co_heading fh5co_heading_border_bottom py-2 mb-4">News</div>
                    </div>
                `;

                if (news.length > 0) {
                    news.forEach(function(item) {
                        // Format the date
                        const date = new Date(item.created_at);
                        const formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        newsHtml += `
                            <div class="row pb-4">
                                <div class="col-md-5">
                                    <div class="fh5co_hover_news_img">
                                        <div class="fh5co_news_img">
                                            <img src="${item.news_image}" alt="${item.title}" style="object-fit: cover;"/>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="col-md-7 animate-box">
                                    <a href="/News/${item.slug}" class="fh5co_magna py-2">${item.title}<br></a>
                                    <a href="/News/${item.slug}" class="fh5co_mini_time py-3">
                                        ${item.name} - ${formattedDate}
                                    </a>
                                    <div class="fh5co_consectetur">
                                        ${item.description}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    newsHtml += `
                        <div class="row pb-4">
                            <div class="col-12 text-center">
                                <p class="fh5co_consectetur">No news found for this category.</p>
                            </div>
                        </div>
                    `;
                }

                // Update the news container
                newsContainer.html(newsHtml);

               // Re-apply animations
                        $('.col-md-7').each(function(index) {
                            $(this)
                                .addClass('animate-box')
                                .attr('data-animate-effect', 'fadeInLeft')
                                .css('opacity', '0')
                                .waypoint(function(direction) {
                                    if (direction === 'down' && !$(this.element).hasClass(
                                            'animated')) {
                                        $(this.element)
                                            .addClass('item-animate')
                                            .delay(index * 100)
                                            .queue(function(next) {
                                                $(this)
                                                    .addClass('fadeInLeft animated')
                                                    .css('opacity', '1');
                                                next();
                                            });
                                    }
                                }, {
                                    offset: '90%'
                                });
                        });

                // Re-initialize hover effects
                $('.fh5co_hover_news_img').hover(
                    function() {
                        $(this).find('.fh5co_news_img').addClass('fh5co_hover_news_img_bus_hover');
                    },
                    function() {
                        $(this).find('.fh5co_news_img').removeClass('fh5co_hover_news_img_bus_hover');
                    }
                );
            } else {
                newsContainer.html(`
                    <div class="alert alert-danger">
                        Failed to load news for the selected category.
                    </div>
                `);
            }
        },
        error: function() {
            newsContainer.html(`
                <div class="alert alert-danger">
                    An error occurred while fetching news.
                </div>
            `);
        }
    });
});
</script>
</body>

</html>
