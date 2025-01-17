<link rel="stylesheet" href="{{ asset('css/frontend/particularnews.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontend.layout.app')
@section('content')
<div class="row">
       <section id="recent-posts" class="col-md-9">
               <div class="post-grid">
                   <article class="featured">
                   <div class="post-image">
                   <img src="{{ asset($news->news_image) }}" class="card-img-top" >
                    </div>
                    <div class="post-content">
                        <h3><strong> Title: </strong>{{ $news['Title'] }}</h3>
                        <p>{{ $news['description'] }}</p>
                        <p class="para"> <strong>create Date: </strong>{{ $news['created_at'] }}</p>
                        <p class="para"> <strong>Update Date: </strong>{{ $news['updated_at'] }}</p>
                    </div>
                   </article>
               </div>
                  
       </section>
       <div class="col-md-3" style="padding-left: 1%; margin-top: 40px;">
       <h4><strong>Related Category News</strong></h4>
             <ul class="list">
               @foreach ($related_news as $news)
               <a href="{{ url('/News/' . $news->slug) }}" class="text-decoration-none text-dark">
                 <li class="li-container"><img src="{{ asset($news->news_image) }}" class="card-img-top" >
                   <h5 class="card-title">{{ $news['title'] }}</h5>
                   </a>
                 </li>
                 @endforeach
             </ul>
       </div>
       </div>
@endsection