<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Country Add</h1>
<form class="simple" method="post" action="/addcountry" >
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>Country Name</label><br>
            <input type="text" id="countryname" name="countryname">
        </div>
        <p>@error('countryname'){{$message}}@enderror</p>


        <div class="submit">
            <button type="submit" class="btn" name="update">Add Country</button>
        </div>
    </div>
</form>
</main>
@endsection