<link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('Backend.layouts.app')
@section('title', 'pages')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Pages List</h2>
        <form class="left" method="post">
            <a href="{{ asset('/page/add') }}">Add-page</a>
        </form>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="filter-container">
            <h4>Filter</h4>
            <div class="filter">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate">
                <button id="filterButton">Filter</button>
            </div>
        </div>

        <table id="Table" class="table table-bordered table-striped" style="width: 1100px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Create Date</th>
                    <th>Update Date</th>
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

    const table = $('#Table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getPagesAjax',
            type: 'POST',
            data: function(d) {

                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
            }
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
                data: 'description',
                name: 'description'
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data, type, row) {
                    return row.time_ago || data;
                }
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                render: function(data, type, row) {
                    return row.time_update_ago || data;
                }
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
        lengthMenu:[5,10,25,50,100]
    });

    $('#filterButton').on('click', function() {
        table.ajax.reload();
    });
});
</script>
@endsection