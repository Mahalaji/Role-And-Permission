<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
<h1 class="header">Page Edit</h1>
<form class="simple" method="post" action="/updatepages">
<div class="form1">
@csrf
    <div class="input-group">
        <label>Title</label><br>
        <input type="text" id="title" name="title" value="{{ old('title', $pages->title) }}">
    </div>
    <p>@error('title'){{$message}}@enderror</p>
    <div class="input-group">

        <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $pages->created_at) }}" readonly>
        <input type="hidden" id="updated_at" name="updated_at" readonly>
        <input type="hidden" id="id" name="id" value="{{ old('id', $pages->id) }}" readonly>

    </div>
    <div class="input-group">
    <label for="editor">Description</label>
    <textarea id="editor" name="description">{{ old('description', $pages->description) }}</textarea>
</div>

    <p>@error('description'){{$message}}@enderror</p>
    <div class="submit">
        <button type="submit" class="btn" name="update">Update Page</button>
    </div>
</div>
</form>
</main>
@endsection
@section('scripts')
<script>
    function lettersOnly(input) {
        var regex = /[^a-z ]/gi;
        input.value = input.value.replace(regex, "");
    }
</script>
<script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script> <!-- Ensure this script matches CKEditor 4 -->
<script>
    CKEDITOR.replace('editor', {
        height: 500, // Set editor height
        resize_enabled: true // Allow resizing
    });
</script>
@endsection
