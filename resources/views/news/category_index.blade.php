<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('layouts.app')
@section('title', 'news category')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>News Category List</h2>
        <form class="left" method="post">
            <a href="{{ asset('/newscategory/add') }}"
                style="padding: 10px; background: azure; text-decoration: none; color: black; border-radius: 5px; font-size: 14px; border: 1px solid black;">Add-Category</a>
        </form>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <table id="NewsCategoryTable" class="table table-bordered table-striped" style="width: 1070px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>seo title</th>
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


    $('#NewsCategoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getNewsCategoryAjax',
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