@extends('Backend.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/Backend/module.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="info" style="background: white;">
    <div class="container mt-4">
        <h2>Module</h2>
        <div class="left">
            <a href="{{ asset('/module/recycle') }}">Recycle</a>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="mt-3">
            <table id="Table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Module Name</th>
                        <th>Add Permission</th>
                        <th>Parent Id</th>
                        <th>Create Date</th>
                        <th>Update Date</th>
                        <th>MVC</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<form method="post" action="/mvctable">
    @csrf
    <input type="hidden" id="hiddenModuleId" name="moduleId" value="">
    <div id="editModuleModal" class="modal">
        <div class="modal-content">
            <button id="closeEditModuleModal" type="button">&times;</button>
            <div class="form-group">
                <label for="dropdownField">Select Table:</label>
                <select id="dropdownField" class="form-control" name="tableName">
                    <option value="" disabled selected>Select Table</option>
                    @foreach($tableNames as $tableName)
                        <option value="{{ $tableName }}">{{ $tableName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-buttons-container">
                <button id="saveModuleData" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</form>

<div id="modal" class="modal">
    <div class="modal-content">
        <button id="closeModal">&times;</button>
        <h4 style="border-bottom: #0056b3 solid 3px">Add Permissions</h4>
        <div class="input-container"></div>
        <div class="modal-buttons-container">
            <button id="AddMore" class="btn btn-primary">Add More</button>
            <button id="savePermissions" class="btn btn-success">Save</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const table = $('#Table').DataTable({
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
                    data: 'addpermissions',
                    orderable: false,
                    searchable: false,
                    render: function () {
                        return `<button class="btn btn-info open-overlay" id="permissionsbtn">Add Permission</button>`;
                    }
                },
                { data: 'parent_id', name: 'parent_id' },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function (data, type, row) {
                        return row.time_ago || data;
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function (data, type, row) {
                        return row.time_update_ago || data;
                    }
                },
                { data: 'MVCcreate', orderable: false, searchable: false },
                { data: 'delete', orderable: false, searchable: false }
            ],
            lengthMenu: [5, 10, 25, 50, 100]
        });

        $('#Table').on('click', '#permissionsbtn', function () {
            const moduleId = $(this).closest('tr').find('td:first').text();
            $('#savePermissions').data('module-id', moduleId);

            $.ajax({
                url: '{{ route('ShowPermissions') }}',
                type: 'POST',
                data: { module_id: moduleId },
                success: function (response) {
                    const permissionsContainer = $('.input-container');
                    permissionsContainer.empty();

                    if (response.status === 'success' && response.data.length > 0) {
                        response.data.forEach(permission => {
                            const newRow = `
                                <div class="input-row">
                                    <label>Permission Name</label>
                                    <input type="text" value="${permission.name}" data-permission-id="${permission.id}" class="permission-input">
                                    <button class="delete-permission btn btn-danger" data-permission-id="${permission.id}">Delete</button>
                                    </div>`;
                            permissionsContainer.append(newRow);
                        });
                    } else {
                        permissionsContainer.append(`
                            <div class="input-row">
                                <label>Permission Name</label>
                                <input type="text" value="" class="permission-input">
                                <button class="delete-permission btn btn-danger">Delete</button>
                            </div>`);
                    }

                    $('#modal').fadeIn(300);
                },
                error: function () {
                    alert('Error fetching permissions. Please try again.');
                }
            });

            $('#closeModal').on('click', function () {
                $('#modal').fadeOut(300);
            });

            $('#AddMore').on('click', function () {
                const newRow = `
                    <div class="input-row">
                        <label>Permission Name</label>
                        <input type="text" value="">
                        <button class="delete-permission btn btn-danger">Delete</button>
                    </div>`;
                $('.input-container').append(newRow);
            });

            $('#savePermissions').on('click', function () {
                const moduleId = $(this).data('module-id');
                let permissions = [];

                $('.input-container .input-row').each(function () {
                    const permissionName = $(this).find('input').val().trim();
                    const permissionId = $(this).find('input').data('permission-id') || null;
                    if (permissionName) {
                        permissions.push({ id: permissionId, name: permissionName });
                    }
                });

                if (permissions.length === 0) {
                    alert('Please add at least one permission.');
                    return;
                }

                $.ajax({
                    url: '/storepermission',
                    type: 'POST',
                    data: {
                        permissions: permissions,
                        module_id: moduleId,
                        guard_name: 'web',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success === true) {
                            alert('Permissions saved successfully.');
                            $('#modal').fadeOut(300);
                            table.ajax.reload();
                        } else {
                            alert('Failed to save permissions.');
                        }
                    },
                    error: function () {
                        alert('Error saving permissions.');
                    }
                });
            });

            //delete
            $('.input-container').on('click', '.delete-permission', function () {
                const permissionId = $(this).data('permission-id');
                const permissionInput = $(this).closest('.input-row').find('input');
                const permissionName = permissionInput.val().trim();

                if (!permissionId && !permissionName) {
                    $(this).closest('.input-row').remove();
                    return;
                }

                if (!permissionId) {
                    alert('This permission has not been saved yet and cannot be deleted.');
                    return;
                }

                if (confirm('Are you sure you want to delete this permission?')) {
                    $.ajax({
                        url: '{{ route('deletePermission') }}',
                        type: 'POST',
                        data: {
                            permission_id: permissionId,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (response) {
                            if (response.success === true) {
                                alert('Permission deleted successfully.');
                                $(`.delete-permission[data-permission-id="${permissionId}"]`).closest('.input-row').remove();
                            } else {
                                alert('Failed to delete permission.');
                            }
                        },
                        error: function () {
                            alert('An error occurred while deleting the permission.');
                        },
                    });
                }
            });

        });

        $(document).on('click', '.editModuleButton', function () {
            const moduleId = $(this).data('id'); 

            $('#hiddenModuleId').val(moduleId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to create MVC?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Select Table',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#editModuleModal').fadeIn(300); // Show the modal
                }
            });
        });

        $('#closeEditModuleModal').on('click', function () {
            $('#editModuleModal').fadeOut(300, function () {
                window.location.href = '/module';
            });
        });



    });
</script>
@endsection