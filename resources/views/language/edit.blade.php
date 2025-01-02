<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Language Add</h1>
<form class="simple" method="post" action="/updatelanguage" enctype="multipart/form-data">
    <div class="form1">
        @csrf
            <div class="input-group">
                <label>Language Name</label><br>
                <input type="text" id="languagename" name="languagename" value="{{old('languagename',$language->languagename)}}">
            </div>
            <p>@error('languagename'){{$message}}@enderror</p>

            <div class="input-group">
                <label>Language Code</label>
                <input type="text" id="languagecode" name="languagecode" value="{{old('languagecode',$language->languagecode)}}">
            </div>
            <p>@error('languagecode'){{$message}}@enderror</p>

            <input type="hidden" id="id" name="id" value="{{old('id',$language->id)}}" readonly>
            <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $language->created_at) }}" readonly>
    <div class="submit">
        <button type="submit" class="btn" name="update">Submit</button>
    </div>
    </div>
</form>
</main>
@endsection
