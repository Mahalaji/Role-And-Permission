<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@extends('layouts.app')
@section('content')
<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Designation List</h2>
      

            <form class="left" method="post">
                <a href="{{ asset('/designation/add') }}"
                    style="padding: 10px; background: azure; text-decoration: none; color: black; border-radius: 5px; font-size: 14px; border: 1px solid black;">Add-Designation</a>
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

            <table id="DesignationTable" class="table table-bordered table-striped" style="width: 1100px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Designation Name</th>
                        <th>Department Name</th>
                        <th>Level</th>
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

    const table = $('#DesignationTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getDesignationAjax',
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
                data: 'designationname',
                name: 'designationname'
            },
            {
                data: 'department.departmentname',
                name: 'department.departmentname'
            },
            {
                data: 'level',
                name: 'level'
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
    });

    $('#filterButton').on('click', function() {
        table.ajax.reload();
    });
});
</script>
@endsection