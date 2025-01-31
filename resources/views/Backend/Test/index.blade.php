@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Test List</h2>
        <a href="/Test/create" class="btn btn-primary">Add Test</a>
        <table id="table" class="table">
            <thead>
                <tr>
                    @foreach($columns as $col)
                        @if($col != 'id')
                            <th>{{ ucfirst($col) }}</th>
                        @endif
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($columns as $col)
                            @if($col != 'id')
                                <td>{{ $row->$col }}</td>
                            @endif
                        @endforeach
                        <td>
                            <a href="/Test/edit/{{ $row->id }}" class="btn btn-warning">Edit</a>
                            <form action="/Test/delete/{{ $row->id }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection