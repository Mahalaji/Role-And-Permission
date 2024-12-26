<link rel="stylesheet" href="{{ asset('css/blogsite/blogcategory.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
 integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontendlayout.app')
@section('content')
<div class="row">
            <section id="recent-posts" >
                <h2>Related Blogs</h2>
                <div class="post-grid">
                    @foreach($related_title_blog as $blog)
                    <article class="featured">
                        <div class="post-image">
                <img src="{{ asset($blog->image) }}" class="card-img-top" >
                        </div>
                        <div class="post-content">
                            <a href="{{ url('/Blogs/' . $blog->slug) }}" class="text-decoration-none text-dark">
                            <h3>{{ $blog->Title }}</h3>    
                          </a> 
                        </div>
                    </article>
                   @endforeach
                </div>
            </section>
</div>
@endsection