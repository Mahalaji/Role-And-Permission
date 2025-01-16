<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Module Add</h1>
    <form class="simple" method="post" action="/addmodule">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Module Name</label><br>
                <input type="text" id="module_name" name="module_name">
            </div>
            <p>@error('module_name'){{$message}}@enderror</p>
            <div class="input-group">
                <label>Parent</label>
                <select id="parent_id" name="parent_id">
                    <option value="">Select Parent</option>
                    @foreach($module as $modules)
                    <option value="{{ $modules->id }}">{{ $modules->module_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <p>@error('parent_id'){{$message}}@enderror</p>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection