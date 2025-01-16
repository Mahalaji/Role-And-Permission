<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Menu Add</h1>
    <form class="simple" method="post" action="/addmenu">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Category</label><br>
                <input type="text" id="category" name="category">
            </div>
            <p>@error('category'){{$message}}@enderror</p>
            <div class="input-group">
                <label>Permission</label><br>
                <input type="text" id="permission" name="permission">
            </div>
            <p>@error('permission'){{$message}}@enderror</p>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection