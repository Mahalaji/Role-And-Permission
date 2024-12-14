<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Add Category</h1>
<form class="simple" method="post" action="/addcategery" >
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

