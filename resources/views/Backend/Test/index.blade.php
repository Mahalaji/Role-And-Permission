@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
@section('content')
<div class="info" style="background: white;">
<div class="container mt-4">
    <h2>Test List</h2>
<a href="/Test/create" class="btn btn-primary">Add Test</a>
<table id="table">
    <thead>
        <tr>
        </div>
        </div><th>Name</th><th>Title</th><th>Updated_at</th><th>Actions</th></tr></thead><tbody>    @foreach($data as $row)
    <tr><td>{{ $row->name }}</td><td>{{ $row->title }}</td><td>{{ $row->updated_at }}</td>            <td>
                <a href="/Test/edit/{{ $row->id }}" class="btn btn-warning">Edit</a>
                <form action="/Test/delete/{{ $row->id }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection