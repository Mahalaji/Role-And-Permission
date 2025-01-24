@extends('Backend.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/Backend/module.css') }}">
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Select Listing</h2>
        <form action="/createmvc" method="POST">
            @csrf
            <div class="form-group">
                @foreach ($columns as $column)
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="column_{{ $loop->index }}" 
                        name="columns[]" 
                        value="{{ $column }}"
                    >
                    <label class="form-check-label" for="column_{{ $loop->index }}">
                        {{ $column }}
                    </label>
                </div>
                @endforeach
            </div>
            
    <input type="hidden"  name="moduleId" value="{{old('moduleId',$moduleId)}}" readonly>
    <input type="hidden"  name="tablename" value="{{old('tablename',$tablename)}}" readonly>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</div>
@endsection
