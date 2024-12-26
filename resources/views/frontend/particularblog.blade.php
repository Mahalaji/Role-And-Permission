<link rel="stylesheet" href="{{ asset('css/blogsite/particularblog.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@extends('frontendlayout.app')
@section('content')
<div class="row">
    <section id="recent-posts" class="col-md-9">
            <div class="post-grid">
                <article class="featured">
                    <div class="post-image" >
                    <img src="{{ asset($blog->image) }}" class="card-img-top" >

                    </div>
                    <div class="post-content">
                        <h3><strong> Title: </strong>{{ $blog['Title'] }}</h3>
                        <!-- <h3><strong> Author Name: </strong>{{ $blog['Name'] }}</h3> -->
                        <p>{{ $blog['Description'] }}</p>
                        <p class="para"> <strong> Post Date: </strong>{{ $blog['post_Date'] }}</p>
                        <p class="para"> <strong> Update Date:</strong>{{ $blog['Update_Date'] }}</p>
                    </div>
                </article>
            </div>
               
    </section>
    <div class="col-md-3" style=" margin-top: 40px;">
        
          <ul class="list">
            <h4><strong>Related Category Blogs</strong></h4>
            @foreach ($related_blogs as $row)
              <a href="{{ url('/Blogs/' . $row->slug) }}" class="text-decoration-none text-dark">
              <li class="li-container"> <img src="{{ asset($row->image) }}" class="card-img-top" >                   
                <h5 class="card-title">{{ $row['Title'] }}</h5>
                </a>
              </li>
              @endforeach
          </ul>
    </div>
    </div>
@endsection