@extends('Backend.layouts.app')
    @section('content')
    <h1>Add Pages</h1>
    <form action="{{ route('pages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id">Id</label>
                <input type="text" name="id" class="form-control" required><label for="title">Title</label>
                <input type="text" name="title" class="form-control" required><label for="description">Description</label>
                <input type="text" name="description" class="form-control" required><label for="created_at">Created_at</label>
                <input type="text" name="created_at" class="form-control" required><label for="updated_at">Updated_at</label>
                <input type="text" name="updated_at" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    @endsection