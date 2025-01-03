<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@extends('layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Department Edit</h1>
<form class="simple" method="post" action="/updateDepartment">
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>Department Name</label><br>
            <input type="text" id="departmentname" name="departmentname"
                value="{{old('departmentname',$department->departmentname)}}">
        </div>
        <p>@error('departmentname'){{$message}}@enderror</p>
        <input type="hidden" id="id" name="id" value="{{old('id',$department->id)}}" readonly>
        <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $department->created_at) }}" readonly>

        <div class="submit">
            <button type="submit" class="btn" name="update">Submit</button>
        </div>
    </div>
</form>
</main>
@endsection