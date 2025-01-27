@extends('Backend.layouts.app')
    @section('content')
    <h1>Edit Pages</h1>
    <form action="{{ route('pages.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id">Id</label>
                <input type="text" name="id" class="form-control" value="{{ $item->id }}" required><label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required><label for="description">Description</label>
                <input type="text" name="description" class="form-control" value="{{ $item->description }}" required><label for="created_at">Created_at</label>
                <input type="text" name="created_at" class="form-control" value="{{ $item->created_at }}" required><label for="updated_at">Updated_at</label>
                <input type="text" name="updated_at" class="form-control" value="{{ $item->updated_at }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
    @endsection