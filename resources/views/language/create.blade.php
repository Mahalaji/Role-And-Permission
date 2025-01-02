<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Language Add</h1>
<form class="simple" method="post" action="/addlanguage" enctype="multipart/form-data">
    <div class="form1">
        @csrf
            <div class="input-group">
                <label>Language Name</label><br>
                <input type="text" id="languagename" name="languagename">
            </div>
            <p>@error('languagename'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Language Code</label>
                <input type="text" id="languagecode" name="languagecode" >
            </div>
            <p>@error('languagecode'){{$message}}@enderror</p>


    <div class="submit">
        <button type="submit" class="btn" name="update">Add Language</button>
    </div>
    </div>
</form>
</main>
@endsection
