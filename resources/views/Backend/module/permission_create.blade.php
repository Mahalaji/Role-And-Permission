<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@extends('Backend.layouts.app')
@section('content')
<main id="main" class="main">
    <h1 class="header">Permission Add</h1>
    <form class="simple" method="post" action="/addpermission">
        <div class="form1">
            @csrf
            <div class="input-group">
                <label>Permission Name</label><br>
                <input type="text" id="permission" name="permission">
            </div>
            <p>@error('module_name'){{$message}}@enderror</p>
            <div class="input-group">
                <input type="hidden" id="id" name="id" value="{{ old('id', $permission->parent_id) }}" readonly>
                <input type="hidden" id="module_name" name="module_name" value="{{ old('module_name', $permission->module_name) }}" readonly>
            </div>
            <div class="submit">
                <button type="submit" class="btn" name="update">Submit</button>
            </div>
        </div>
    </form>
</main>
@endsection