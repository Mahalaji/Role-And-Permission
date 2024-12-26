<link rel="stylesheet" href="{{ asset('css/blogsite/news.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontendlayout.app')
@section('content')
<div class="row">
            <section id="recent-posts" class="col-md-9">
                <h2>Recent News</h2>
                <div class="post-grid">
                    @foreach($newsview->take(2) as $news)

                    <article class="featured">
                        <div class="post-image">
                        <img src="{{ asset($news->Image) }}" class="card-img-top" >
                        </div>
                        <div class="post-content">
                            <a href="{{ url('/News/' . $news->slug) }}" class="text-decoration-none text-dark">
                                <h3>{{$news->Title}}</h3>
                            </a>
                        </div>
                    </article>
                    @endforeach

                </div>
                <a href="#" id="loadMore">Load More</a>
            </section>
            <div class="col-md-3" style=" margin-top: 40px;">
                <h5><strong>News Categories:-</strong></h5>
                <h5 class="form-like">
                    <ul class="list">
                    @foreach ($categories as $row)
                <a href="{{ url('/newstitle/' . $row->seo_title) }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{$row->seo_title}}</h5>
                </a>
                </li>
                @endforeach
                    </ul>
                </h5>
            </div>
        </div>
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let itemsToShow = 2; 
        let offset = 2;

        $("#loadMore").on("click", function(e) {
            e.preventDefault();

           
            $.ajax({
                url: '/ajaxnews', 
                type: 'GET',
                data: {
                    offset: offset,  
                    limit: itemsToShow 
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const news = response.data; 
                        console.log(response);
                        console.log(offset);
                        if (news.length > 0) {
                           console.log(news);
                           news.forEach(function(news) {
                                const blogHtml = `
                                    <article class="featured">

                <div class="post-image">
                <img src=" ${news.Image}" class="card-img-top" >
                </div>
                <div class="post-content">
                <a href="/Blogs/ ${news.slug}" class="text-decoration-none text-dark">
                        <h3>${news.Title}</h3>
                    </a>
                </div>
            </article>
                                `;
                                $('.post-grid').append(blogHtml); 
                            });
                            offset += itemsToShow;
                            if (offset >= response.count) {
                                $('#loadMore').hide();
                            }
                        }
                    } else {
                        alert('Failed to load news.');
                    }
                },
                error: function() {
                    alert('An error occurred while loading more news.');
                }
            });
        });
    });
</script>
@endsection