<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Menu Edit</h1>
    <form class="simple" method="post" action="/editmenu">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Category</label><br>
                <input type="text" id="category" name="category" value="{{ old('category', $editmenu->category) }}">
            </div>
            <p>@error('category'){{$message}}@enderror</p>
            <div class="input-group">
                <label>Permission</label><br>
                <input type="text" id="permission" name="permission" value="{{ old('permission', $editmenu->permission) }}">
            </div>
            <p>@error('permission'){{$message}}@enderror</p>
            <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $editmenu->created_at) }}" readonly>
                <input type="hidden" id="id" name="id" value="{{ old('id', $editmenu->id) }}" readonly>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection