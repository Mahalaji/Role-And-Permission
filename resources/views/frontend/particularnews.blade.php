<link rel="stylesheet" href="{{ asset('css/blogsite/particularnews.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontendlayout.app')
@section('content')
<div class="row">
       <section id="recent-posts" class="col-md-9">
               <div class="post-grid">
                   <article class="featured">
                   <div class="post-image">
                   <img src="{{ asset($news->Image) }}" class="card-img-top" >
                    </div>
                    <div class="post-content">
                        <h3><strong> Title: </strong>{{ $news['Title'] }}</h3>
                        <p>{{ $news['Description'] }}</p>
                        <p class="para"> <strong>Post Date: </strong>{{ $news['Date'] }}</p>
                        <p class="para"> <strong>Update Date: </strong>{{ $news['Update_Date'] }}</p>
                    </div>
                   </article>
               </div>
                  
       </section>
       <div class="col-md-3" style="padding-left: 100px; margin-top: 40px;">
       <h4><strong>Related Category News</strong></h4>
             <ul class="list">
               @foreach ($related_news as $news)
               <a href="{{ url('/News/' . $news->slug) }}" class="text-decoration-none text-dark">
                 <li class="li-container"><img src="{{ asset($news->Image) }}" class="card-img-top" >
                   <h5 class="card-title">{{ $news['Title'] }}</h5>
                   </a>
                 </li>
                 @endforeach
             </ul>
       </div>
       </div>
@endsection