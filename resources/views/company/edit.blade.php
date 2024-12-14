<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Edit Company</h1>
<form class="simple" method="post" action="/updatecompany" >
    <div class="form1">

        @csrf
        <div class="input-group">
            <label>Company Name</label><br>
            <input type="text" id="companyname" name="companyname" value="{{old('companyname',$company->companyname)}}">
        </div>
        <p>@error('companyname'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Company Type</label>
            <input type="text" id="companytype" name="companytype" value="{{old('companytype',$company->companytype)}}">
        </div>
        <p>@error('companytype'){{$message}}@enderror</p>

        <div class="input-group">
            <label>Company Email</label>
            <input type="text" id="companyemail" name="companyemail" value="{{old('companyemail',$company->companyemail)}}">
        </div>
        <p>@error('companyemail'){{$message}}@enderror</p>
        <input type="hidden" id="id" name="id" value="{{old('id',$company->id)}}">

        <div class="submit">
            <button type="submit" class="btn" name="update">Update Company</button>
        </div>
    </div>
</form>

</main>
@endsection
