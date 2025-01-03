<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('layouts.app')
@section('title', 'Blogs')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Blogs Category List</h2>
        <form class="left" method="post">
            <a href="{{ asset('/blogcategory/add') }}"
                style="padding: 10px; background: azure; text-decoration: none; color: black; border-radius: 5px; font-size: 14px; border: 1px solid black;">Add-Category</a>
        </form>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <table id="BlogsCategoryTable" class="table table-bordered table-striped" style="width: 1070px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>seo title</th>
                    <th>Blogs</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#BlogsCategoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getBlogsCategoryAjax',
            type: 'POST',
        },
        pageLength: 5,
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'blogs_count',
                name: 'blogs_count'
            },
            {
                data: 'edit',
                orderable: false,
                searchable: false
            },
            {
                data: 'delete',
                orderable: false,
                searchable: false
            },
        ],
    });
});
</script>
@endsection