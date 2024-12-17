@extends('layouts.app')
@section('title', 'news')

@section('content')
<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Module</h2>

        <form class="left" method="post">
        <a href="{{ asset('/module/add') }}"
        style="padding: 10px; background: azure; text-decoration: none; color: black; border-radius: 5px; font-size: 14px; border: 1px solid black;">
            Add-Module
        </a>
        </form>
        <div class="mt-3">
            <table id="ModuleTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Module Name</th>
                        <th>Add Permission</th>
                        <th>Parent Id</th>
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
</div>

<!-- Overlay for Add Permission -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999;">
    <div style="background: white; border-radius: 8px; max-width: 500px; margin: 50px auto; padding: 20px; position: relative;">
        <h4>Add Permission</h4>
        <form id="permission-form">
            <div id="permission-container">
                <!-- Initial Input Row -->
                <div class="permission-row mb-3 d-flex align-items-center">
                    <input type="text" name="name[]" class="form-control me-2" placeholder="Enter Name" required>
                    <button type="button" class="btn btn-danger delete-row">Delete</button>
                </div>
            </div>
            <button type="button" id="add-more" class="btn btn-success mb-2">Add More</button>
            <div class="d-flex justify-content-between">
                <button type="button" id="close-overlay" class="btn btn-secondary">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
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

    // DataTable Initialization
    const table = $('#ModuleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/getModuleAjax',
            type: 'POST'
        },
        pageLength: 5,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'module_name', name: 'module_name' },
            { 
                data: 'addpermission', 
                orderable: false, 
                searchable: false,
                render: function() {
                    return `<button class="btn btn-info open-overlay">Add Permission</button>`;
                }
            },
            { data: 'parent_id', name: 'parent_id' },
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
            { data: 'edit', orderable: false, searchable: false },
            { data: 'delete', orderable: false, searchable: false },
        ]
    });

    // Open Overlay
    $(document).on('click', '.open-overlay', function() {
        $('#overlay').fadeIn();
    });

    // Close Overlay
    $('#close-overlay').click(function() {
        $('#overlay').fadeOut();
    });

    // Add More Input Rows
    $('#add-more').click(function() {
        const newRow = `
            <div class="permission-row mb-3 d-flex align-items-center">
                <input type="text" name="name[]" class="form-control me-2" placeholder="Enter Name" required>
                <button type="button" class="btn btn-danger delete-row">Delete</button>
            </div>`;
        $('#permission-container').append(newRow);
    });

    // Delete Row
    $(document).on('click', '.delete-row', function() {
        $(this).closest('.permission-row').remove();
    });

    $('#permission-form').submit(function(event) {
        event.preventDefault();

        const formData = $(this).serialize();
        $.ajax({
            url: '/addPermission', 
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Permissions added successfully!');
                $('#overlay').fadeOut();
                $('#permission-form')[0].reset();
                table.ajax.reload();
            },
            error: function() {
                alert('An error occurred while adding permissions.');
            }
        });
    });
});
</script>
@endsection
