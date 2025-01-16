<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
<h1 class="header">Edit Category</h1>
<form class="simple" method="post" action="/updatecategery" >
<div class="form1">
@csrf
    <div class="input-group">
        <label>Seo Title</label><br>
        <input type="text" id="title" name="title" value="{{ old('title', $blogcategory->title) }}">
    </div>
    <p>@error('title'){{$message}}@enderror</p>
    <input type="hidden" id="id" name="id" value="{{ old('id', $blogcategory->id) }}">

    <div class="submit">
        <button type="submit" class="btn" name="update">Update Category</button>
    </div>
</div>
</form>
</main>
@endsection

