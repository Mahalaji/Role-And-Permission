<link rel="stylesheet" href="{{ asset('css/blogsite/blogcategory.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
 integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontendlayout.app')
@section('content')
<div class="row">
            <section id="recent-posts" >
                <h2>Related News</h2>
                <div class="post-grid">
                    @foreach($related_title_news as $news)
                    <article class="featured">
                        <div class="post-image">
                <img src="{{ asset($news->Image) }}" class="card-img-top" >
                        </div>
                        <div class="post-content">
                            <a href="{{ url('/News/' . $news->slug) }}" class="text-decoration-none text-dark">
                            <h3>{{ $news->Title }}</h3>    
                          </a> 
                        </div>
                    </article>
                   @endforeach
                </div>
            </section>
</div>
@endsection