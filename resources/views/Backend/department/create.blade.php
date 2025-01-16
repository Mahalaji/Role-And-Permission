<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Department Add</h1>
<form class="simple" method="post" action="/adddepartment" >
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>Department Name</label><br>
            <input type="text" id="departmentname" name="departmentname">
        </div>
        <p>@error('departmentname'){{$message}}@enderror</p>


        <div class="submit">
            <button type="submit" class="btn" name="update">Add Department</button>
        </div>
    </div>
</form>
</main>
@endsection