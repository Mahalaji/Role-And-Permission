@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@section('content')
<main id="main" class="main">
<h1 class="header">Create Test</h1>
<form action="/Test/store" method="POST">
<div class="form1">
    @csrf
    <div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
</div><div class="form-group">
    <label for="created_at">Created_at</label>
    <input type="text" class="form-control" id="created_at" name="created_at" placeholder="Enter Created_at">
</div><div class="form-group">
    <label for="updated_at">Updated_at</label>
    <input type="text" class="form-control" id="updated_at" name="updated_at" placeholder="Enter Updated_at">
</div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection