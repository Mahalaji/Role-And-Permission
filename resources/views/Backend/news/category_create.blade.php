<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
<h1 class="header">Add Category</h1>
<form class="simple" method="post" action="/createnewscategory" >
<div class="form1">
@csrf
<div class="input-group">
        <label>Title</label><br>
        <input type="text" id="title" name="title">
    </div>
    <p>@error('title'){{$message}}@enderror</p>

    <div class="submit">
        <button type="submit" class="btn" name="update">Add Category</button>
    </div>
</div>
</form>
</main>
@endsection

