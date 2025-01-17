<link rel="stylesheet" href="{{ asset('css/frontend/blogs.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

@extends('frontend.layout.app')
@section('content')
<div class="row">
<div class="col-md-3" style="margin-top: 40px;">
        <h5><strong>Blog Categories:</strong></h5>
        <h5 class="form-like">
            <ul class="list">
                @foreach ($categories as $row)
                    <li>
                        <a href="javascript:void(0);" class="category-link text-decoration-none text-dark"
                            data-category="{{ $row->id }}">
                            <h5 class="card-title">{{ $row->title }}</h5>
                        </a>
                    </li>
                @endforeach
            </ul>
        </h5>
    </div>
    <section id="recent-posts" class="col-md-9">
        <h2>Recent Blogs</h2>
        <div class="post-grid">
            @foreach($Blogs->take(2) as $row)
                <article class="featured">
                    <div class="post-image">
                        <img src="{{ asset($row->image) }}" class="card-img-top">
                    </div>
                    <div class="post-content">
                        <a href="{{ url('/Blogs/' . $row->slug) }}" class="text-decoration-none text-dark">
                            <h3>{{ $row->title }}</h3>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
        <a href="#" id="load-more">Load More</a>
    </section>
  
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let itemsToShow = 2;
        let offset = 2;

        // Load More Button Logic
        $("#load-more").on("click", function (e) {
            e.preventDefault();

            $.ajax({
                url: '/ajaxblogs',
                type: 'GET',
                data: {
                    offset: offset,
                    limit: itemsToShow
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const blogs = response.data;
                        if (blogs.length > 0) {
                            blogs.forEach(function (blog) {
                                const blogHtml = `
                                    <article class="featured">
                                        <div class="post-image">
                                            <img src="${blog.image}" class="card-img-top">
                                        </div>
                                        <div class="post-content">
                                            <a href="/Blogs/${blog.slug}" class="text-decoration-none text-dark">
                                                <h3>${blog.Title}</h3>
                                            </a>
                                        </div>
                                    </article>
                                `;
                                $('.post-grid').append(blogHtml);
                            });
                            offset += itemsToShow;
                            if (offset >= response.count) {
                                $('#load-more').hide(); // Hide button when no more blogs
                            }
                        }
                    } else {
                        alert('Failed to load blogs.');
                    }
                },
                error: function () {
                    alert('An error occurred while loading more blogs.');
                }
            });
        });

        // Category Selection Logic
        $(".category-link").on("click", function (e) {
            e.preventDefault();

            const categoryId = $(this).data("category"); // Get the category ID

            // Clear existing blogs and hide the "Load More" button
            $(".post-grid").empty();
            $("#load-more").hide();

            // Make AJAX request to fetch blogs for the selected category
            $.ajax({
                url: '/ajaxblogs/category', // Update the route as needed
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const blogs = response.data;
                        if (blogs.length > 0) {
                            blogs.forEach(function (blog) {
                                const blogHtml = `
                                    <article class="featured">
                                        <div class="post-image">
                                            <img src="${blog.image}" class="card-img-top">
                                        </div>
                                        <div class="post-content">
                                            <a href="/Blogs/${blog.slug}" class="text-decoration-none text-dark">
                                                <h3>${blog.title}</h3>
                                            </a>
                                        </div>
                                    </article>
                                `;
                                $(".post-grid").append(blogHtml);
                            });
                        } else {
                            $(".post-grid").append("<p>No blogs found for this category.</p>");
                        }
                    } else {
                        alert("Failed to load blogs for the selected category.");
                    }
                },
                error: function () {
                    alert("An error occurred while fetching blogs.");
                }
            });
        });
    });
</script>
@endsection