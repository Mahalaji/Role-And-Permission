<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Company Add</h1>
<form class="simple" method="post" action="/createcompany" >
    <div class="form1">

        @csrf
        <div class="input-group">
            <label>Company Name</label><br>
            <input type="text" id="companyname" name="companyname">
        </div>
        <p>@error('companyname'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Company Type</label>
            <input type="text" id="companytype" name="companytype" >
        </div>
        <p>@error('companytype'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Company Email</label>
            <input type="text" id="companyemail" name="companyemail">
        </div>
        <p>@error('companyemail'){{$message}}@enderror</p>

        <div class="submit">
            <button type="submit" class="btn" name="update">Add Company</button>
        </div>
    </div>
</form>

</main>
@endsection
