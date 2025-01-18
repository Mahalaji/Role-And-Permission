<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Country Edit</h1>
<form class="simple" method="post" action="/updateCountry">
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>Country Name</label><br>
            <input type="text" id="name" name="name"
                value="{{old('name',$country->name)}}">
        </div>
        <p>@error('name'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Country Code</label><br>
            <input type="text" id="country_code" name="country_code"
                value="{{old('country_code',$country->country_code)}}">
        </div>
        <p>@error('country_code'){{$message}}@enderror</p>
        <input type="hidden" id="id" name="id" value="{{old('id',$country->id)}}" readonly>
        <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $country->created_at) }}" readonly>

        <div class="submit">
            <button type="submit" class="btn" name="update">Submit</button>
        </div>
    </div>
</form>
</main>
@endsection