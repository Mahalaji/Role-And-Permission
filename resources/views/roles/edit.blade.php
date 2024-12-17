@extends('layouts.app')
@section('content')
<style>
.dropdown-menu {
    display: none;
    position: static !important;
    width: 100%;
    transform: none !important;
    background-color: transparent;
    box-shadow: none;
    border: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-toggle {
    background-color: transparent;
    border: none;
    color: #565454;
}

.content {
    padding: 30px 30px 0 30px;
    flex-grow: 1;
    width: 50vw;
    margin: 20px auto;
    border-radius: 7px;
    background-color: #e0e0e0;
}

.row {
    gap: 20px;
}

.dropdown {
    margin-bottom: 10px;
}
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i>
                Back</a>
        </div>
    </div>
</div>

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('roles.update', $role->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $role->name }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permission:</strong>
                <br />
                @foreach($permission as $value)
                <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name"
                        {{ in_array($value->id, $rolePermissions) ? 'checked' : ''}}>
                    {{ $value->name }}</label>
                <br />
                @endforeach
                <div class="dropdown"></div>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="blogsDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Blogs
                </button>
                <?php
                  $module = json_decode($module, true);
                 ?>
                @foreach ( $module as $modules )
                @if (Str::startsWith(strtolower($modules->module_name), 'blog'))
                <div class="dropdown-item">
                    <input type="checkbox" name="permission[{{ $modules->id }}]" value="{{ $modules->id }}"
                        class="name">
                    {{ $modules->module_name }}
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa-solid fa-floppy-disk"></i>
                Submit</button>
        </div>
    </div>
</form>

@endsection
<!-- <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="blogsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Blogs
                    </button>
                    <div class="dropdown-menu" aria-labelledby="blogsDropdown">
                        @foreach($permission as $value)
                            @if (Str::startsWith(strtolower($value->name), 'blog'))
                                <div class="dropdown-item">
                                    <input type="checkbox" name="permission[{{ $value->id }}]" value="{{ $value->id }}" 
                                    class="name" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                    {{ $value->name }}
                                </div> -->