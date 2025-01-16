<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Submodule Add</h1>
    <form class="simple" method="post" action="/addsubmodule">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Module Name</label><br>
                <input type="text" id="module_name" name="module_name">
            </div>
            <p>@error('module_name'){{$message}}@enderror</p>
            <div class="input-group">
                <input type="hidden" id="id" name="id" value="{{ old('id', $module->id) }}" readonly>
            </div>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection