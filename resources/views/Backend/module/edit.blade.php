<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Submodule Add</h1>
    <form class="simple" method="post" action="/editmodule">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Module Name</label><br>
                <input type="text" id="module_name" name="module_name" value="{{ old('module_name', $editmodule->module_name) }}">
            </div>
            <p>@error('module_name'){{$message}}@enderror</p>
            <div class="input-group">
            <input type="hidden" id="created_at" name="created_at" value="{{ old('created_at', $editmodule->created_at) }}" readonly>
                <input type="hidden" id="id" name="id" value="{{ old('id', $editmodule->id) }}" readonly>
            </div>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection