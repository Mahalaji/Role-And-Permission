@extends('Backend.layouts.app')
    @section('content')
    <h1>Pages List</h1>
    <a href="{{ route('pages.create') }}" class="btn btn-success">Add New</a>
    <table class="table">
        <thead>
            <tr>
                <th>{{ ucfirst('id') }}</th><th>{{ ucfirst('title') }}</th><th>{{ ucfirst('description') }}</th><th>{{ ucfirst('created_at') }}</th><th>{{ ucfirst('updated_at') }}</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->id }}</td><td>{{ $row->title }}</td><td>{{ $row->description }}</td><td>{{ $row->created_at }}</td><td>{{ $row->updated_at }}</td>
                <td>
                    <a href="{{ route('pages.edit', $row->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('pages.destroy', $row->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection