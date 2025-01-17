<link rel="stylesheet" href="{{ asset('css/frontend/news.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontend.layout.app')

@section('content')
<div class="row">
<div class="col-md-3" style="margin-top: 40px;">
        <h5><strong>News Categories:</strong></h5>
        <h5 class="form-like">
        <ul class="list">
            @foreach ($categories as $category)
                <li>
                    <a href="javascript:void(0);" class="category-link text-decoration-none text-dark"
                        data-category="{{ $category->id }}">
                        <h5 class="card-title">{{ $category->title }}</h5>
                    </a>
                </li>
            @endforeach
        </ul>
        </h5>
    </div>
    <section id="recent-posts" class="col-md-9">
        <h2>Recent News</h2>
        <div class="post-grid">
            @foreach($newsview->take(2) as $news)
                <article class="featured">
                    <div class="post-image">
                        <img src="{{ asset($news->news_image) }}" class="card-img-top">
                    </div>
                    <div class="post-content">
                        <a href="{{ url('/News/' . $news->slug) }}" class="text-decoration-none text-dark">
                            <h3>{{ $news->title }}</h3>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
        <a href="#" id="loadMore" class="btn btn-primary mt-3">Load More</a>
    </section>

   
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let itemsToShow = 2; // Number of items to load each time
        let offset = 2; // Starting offset for loading news

        // Load More functionality
        $("#loadMore").on("click", function (e) {
            e.preventDefault();

            $.ajax({
                url: '/ajaxnews', // AJAX route to fetch news
                type: 'GET',
                data: {
                    offset: offset,
                    limit: itemsToShow
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const news = response.data;
                        if (news.length > 0) {
                            news.forEach(function (item) {
                                const newsHtml = `
                                    <article class="featured">
                                        <div class="post-image">
                                            <img src="${item.image}" class="card-img-top">
                                        </div>
                                        <div class="post-content">
                                            <a href="/News/${item.slug}" class="text-decoration-none text-dark">
                                                <h3>${item.title}</h3>
                                            </a>
                                        </div>
                                    </article>
                                `;
                                $(".post-grid").append(newsHtml);
                            });
                            offset += itemsToShow;
                            if (offset >= response.count) {
                                $("#loadMore").hide(); // Hide "Load More" if no more items
                            }
                        }
                    } else {
                        alert("Failed to load news.");
                    }
                },
                error: function () {
                    alert("An error occurred while loading more news.");
                }
            });
        });

        // Category filter functionality
        $(document).on("click", ".category-link", function (e) {
            e.preventDefault();

            const categoryId = $(this).data("category");

            // Clear existing news and hide "Load More" button
            $(".post-grid").empty();
            $("#loadMore").hide();

            $.ajax({
                url: '/ajaxnews/category', // AJAX route for category filter
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const news = response.data;
                        if (news.length > 0) {
                            news.forEach(function (item) {
                                const newsHtml = `
                                    <article class="featured">
                                        <div class="post-image">
                                            <img src="${item.image}" class="card-img-top">
                                        </div>
                                        <div class="post-content">
                                            <a href="/News/${item.slug}" class="text-decoration-none text-dark">
                                                <h3>${item.title}</h3>
                                            </a>
                                        </div>
                                    </article>
                                `;
                                $(".post-grid").append(newsHtml);
                            });
                        } else {
                            $(".post-grid").append("<p>No news found for this category.</p>");
                        }
                    } else {
                        alert("Failed to load news for the selected category.");
                    }
                },
                error: function () {
                    alert("An error occurred while fetching news.");
                }
            });
        });
    });
</script>
@endsection
