<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main"></main>
<h1 class="header">Designation Edit</h1>
<form class="simple" method="post" action="/updateDesignation" >
    <div class="form1">
        @csrf
        <div class="input-group">
            <label>Designation Name</label><br>
            <input type="text" id="designationname" name="designationname" value="{{old('designationname',$designation->designationname)}}">
        </div>
        <p>@error('designationname'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Department</label>
            <select id="department_id" name="department_id">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->departmentname }}
                </option>
                @endforeach
            </select>
        </div>
        <p>@error('department_id'){{$message}}@enderror</p>
        <div class="input-group">
            <label>Level</label>
            <select name="level" id="level">
                <option value="">Select Level</option>
                <option value="level 1">Level 1</option>
                <option value="level 2">Level 2</option>
                <option value="level 3">Level 3</option>
                <option value="level 4">Level 4</option>
                <option value="level 5">Level 5</option>
            </select>
        </div>
        <p>@error('level'){{$message}}@enderror</p>
        
        <input type="hidden" id="id" name="id" value="{{old('id',$designation->id)}}" readonly>
        <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $designation->created_at) }}" readonly>
        <div class="submit">
            <button type="submit" class="btn" name="update">Submit</button>
        </div>
    </div>
</form>
</main>
@endsection