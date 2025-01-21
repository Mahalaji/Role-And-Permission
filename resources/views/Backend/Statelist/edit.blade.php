<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">State Edit</h1>
<form class="simple" method="post" action="/updatestate">
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>State Name</label><br>
            <input type="text" id="name" name="name"
                value="{{old('name',$state->name)}}">
        </div>
        <p>@error('name'){{$message}}@enderror</p>

        <input type="hidden" id="id" name="id" value="{{old('id',$state->id)}}" readonly>
        <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $state->created_at) }}" readonly>

        <div class="submit">
            <button type="submit" class="btn" name="update">Submit</button>
        </div>
    </div>
</form>
</main>
@endsection